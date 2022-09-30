<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;

class OrderingController extends Controller
{
    public function index()
    {
        return view('ordering.index', [
            'tables' => Table::all(),
        ]);
    }

    public function fillIndexForm(Request $request)
    {
        $validatedData = $request->validate([
            'phone_num' => ['required', 'numeric', 'min:10'],
            'table_number' => ['required', 'numeric'],
        ]);

        $phone_num = $validatedData['phone_num'];
        $table_number = $validatedData['table_number'];
        $customer_id = Customer::firstOrCreate(['phone_num' => $phone_num])->id;

        $this->checkIfCartExists($customer_id);
        $this->checkIfOrderExists($customer_id);
        $this->createCartAndOrder($customer_id, $table_number);

        return redirect('/' . $customer_id . '/menus');
    }

    public function checkIfCartExists($customer_id)
    {
        $carts = Cart::where('customer_id', $customer_id)->get();

        if ($carts->isNotEmpty()) {
            foreach ($carts as $cart) {
                $cart_id = $cart->id;
                $cartMenus = $cart->menus();
                if ($cartMenus->get()->isNotEmpty()) {
                    $cartMenus->detach();
                }
                Cart::destroy($cart_id);
            }
        }
    }

    public function checkIfOrderExists($customer_id)
    {
        $orders = Order::where('customer_id', $customer_id)->get();

        if ($orders->isNotEmpty()) {
            foreach ($orders as $order) {
                $order_id = $order->id;
                $menuOrder = $order->menus()->get();
                if ($menuOrder->isEmpty()) {
                    Order::destroy($order_id);
                }
            }
        }
    }

    public function createCartAndOrder($customer_id, $table_number)
    {
        $table_id = Table::where('table_number', $table_number)
            ->first()->id;

        Cart::create(['customer_id' => $customer_id]);
        Order::create([
            'customer_id' => $customer_id,
            'table_id' => $table_id,
            'prepare_status' => 0,
            'serve_status' => 0,
            'estimate_time' => 0,
            'payment_status' => 0
        ]);
    }

    public function getMenus($customer_id)
    {
        $totalPrice = 0;
        $cart = Cart::where('customer_id', $customer_id)->first();
        $cartMenus = $cart->menus()->get();

        if ($cartMenus->isNotEmpty()) {
            foreach ($cartMenus as $cartMenu) {
                $price = ($cartMenu->price) * ($cartMenu->pivot->quantity);
                $totalPrice += $price;
            }
        }

        return view('ordering.menus', [
            'customer_id' => $customer_id,
            'menus' => Menu::oldest()->filter(request(['search']))->get(),
            'categories' => Category::all(),
            'totalPrice' => $totalPrice
        ]);
    }

    public function getMenu($customer_id, Menu $menu)
    {
        $category_id = Category::where('name', 'Sides')->first()->id;

        return view('ordering.menu', [
            'customer_id' => $customer_id,
            'menu' => $menu,
            'sides' => Menu::where('category_id', $category_id)->get()
        ]);
    }

    public function addMenuToCart(Request $request, $customer_id, $menu_id)
    {
        $quantity = $request->quantity;
        $remarks = ($request->remarks) ? $request->remarks : 'N/A';
        $sides = ($request->sides) ? implode(', ', $request->sides) : 'N/A';

        $menuToCartData = array(
            'quantity' => $quantity,
            'remarks' => $remarks,
            'sides' => $sides
        );

        $cartMenus = Cart::where('customer_id', $customer_id)->first()->menus();

        if ($cartMenus->get()->contains($menu_id)) {
            $cartMenu = $cartMenus->get()->where('id', $menu_id)->first();
            $pivotQuantity = $cartMenu->pivot->quantity;
            $cartMenus->updateExistingPivot($menu_id, [
                'quantity' => $pivotQuantity + $quantity
            ]);
        } else {
            $cartMenus->attach($menu_id, $menuToCartData);
        }

        $menu = Menu::where('id', $menu_id)->first();
        $available_quantity = $menu->available_quantity;
        $remaining_quantity = $available_quantity - $quantity;
        $menu->update(['available_quantity' => $remaining_quantity]);

        if ($remaining_quantity <= 0) {
            $menu->update(['availability' => 1]);
        }

        return redirect('/' . $customer_id . '/menus');
    }

    // TODO: Implement estimated preparation time calculation
    public function confirmOrder($customer_id)
    {
        $totalPrice = 0;
        $estimatedTime = 0;
        $cart = Cart::where('customer_id', $customer_id)->first();
        $cartMenus = $cart->menus()->get();

        foreach ($cartMenus as $cartMenu) {
            $price = ($cartMenu->price) * ($cartMenu->pivot->quantity);
            $totalPrice += $price;
            $estimatedTime += $cartMenu->preparation_time;
        }

        return view('ordering.confirm', [
            'cart_id' => $cart->id,
            'customer_id' => $customer_id,
            'cartMenus' => $cartMenus,
            'totalPrice' => $totalPrice,
            'estimatedTime' => $estimatedTime
        ]);
    }

    public function deleteMenuFromCart($customer_id, $menu_id)
    {
        $cart = Cart::where('customer_id', $customer_id)->first();
        $cartMenu = $cart->menus()->get()
            ->where('id', $menu_id)->first();
        $available_quantity = $cartMenu->available_quantity;
        $cartMenu_quantity = $cartMenu->pivot->quantity;

        $remaining_quantity = $available_quantity + $cartMenu_quantity;
        $cartMenu->update(['available_quantity' => $remaining_quantity]);

        if ($remaining_quantity > 0) {
            $cartMenu->update(['availability' => 0]);
        }

        $cart->menus()->detach($menu_id);
        return redirect('/' . $customer_id . '/cart/confirm');
    }

    public function clearCart($customer_id)
    {
        $cart = Cart::where('customer_id', $customer_id)->first();
        $cartMenus = $cart->menus();

        if ($cartMenus->get()->isNotEmpty()) {
            foreach ($cartMenus->get() as $cartMenu) {
                $available_quantity = $cartMenu->available_quantity;
                $cartMenu_quantity = $cartMenu->pivot->quantity;
                $remaining_quantity = $available_quantity + $cartMenu_quantity;
                $cartMenu->update(['available_quantity' => $remaining_quantity]);

                if ($remaining_quantity > 0) {
                    $cartMenu->update(['availability' => 0]);
                }
            }
            $cartMenus->detach();
        }

        return redirect('/' . $customer_id . '/menus');
    }

    // TODO: Implement estimated preparation time calculation
    public function orderConfirmed($customer_id)
    {
        $totalPrice = 0;
        $estimatedTime = 0;
        $order = Order::where('customer_id', $customer_id)->get()->last();
        $cart = Cart::where('customer_id', $customer_id)->first();
        $cartMenus = $cart->menus()->get();

        foreach ($cartMenus as $cartMenu) {
            $price = ($cartMenu->price) * ($cartMenu->pivot->quantity);
            $totalPrice += $price;
            $estimatedTime += $cartMenu->preparation_time;
            $menu_id = $cartMenu->id;

            $order->menus()->attach($menu_id, [
                'quantity' => $cartMenu->pivot->quantity,
                'menu_prepare' => 0,
                'menu_serve' => 0,
                'remarks' => $cartMenu->pivot->remarks,
                'sides' => $cartMenu->pivot->sides
            ]);
        }

        $order->update(['estimate_time' => $estimatedTime]);
        return view('ordering.confirmed', [
            'totalPrice' => $totalPrice,
            'estimatedTime' => $estimatedTime
        ]);
    }
}
