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

    // search functionality not yet implemented
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
            // 'menus' => Menu::all()->filter(request(['search'])),
            'menus' => Menu::all(),
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
        $sides = ($request->sides) ? implode(", ", $request->sides) : $request->sides;

        $menuToCartData = array(
            'quantity' => $request->quantity,
            'remarks' => $request->remarks,
            'sides' => $sides
        );

        $cartMenus = Cart::where('customer_id', $customer_id)->first()->menus();

        if ($cartMenus->get()->contains($menu_id)) {
            $cartMenu = $cartMenus->get()->where('id', $menu_id)->first();
            $quantity = $cartMenu->pivot->quantity;
            $cartMenus->updateExistingPivot($menu_id, [
                'quantity' => $quantity + 1
            ]);
        } else {
            $cartMenus->attach($menu_id, $menuToCartData);
        }

        return redirect('/' . $customer_id . '/menus');
    }

    public function confirmOrder($customer_id)
    {
        $totalPrice = 0;
        $cart = Cart::where('customer_id', $customer_id)->first();
        $cart_id = $cart->id;
        $cartMenus = $cart->menus()->get();

        foreach ($cartMenus as $cartMenu) {
            $price = ($cartMenu->price) * ($cartMenu->pivot->quantity);
            $totalPrice += $price;
        }

        return view('ordering.confirm', [
            'cart_id' => $cart_id,
            'customer_id' => $customer_id,
            'cartMenus' => $cartMenus,
            'totalPrice' => $totalPrice
        ]);
    }

    public function deleteMenuFromCart($customer_id, $menu_id)
    {
        $cart = Cart::where('customer_id', $customer_id)->first();
        $cart->menus()->detach($menu_id);

        return redirect('/' . $customer_id . '/cart/confirm');
    }

    public function clearCart($customer_id)
    {
        $cart = Cart::where('customer_id', $customer_id)->first();
        $cartMenus = $cart->menus();

        if ($cartMenus->get()->isNotEmpty()) {
            $cartMenus->detach();
        }

        return redirect('/' . $customer_id . '/menus');
    }

    public function orderConfirmed($customer_id)
    {
        $totalPrice = 0;
        $order = Order::where('customer_id', $customer_id)->get()->last();
        $cart = Cart::where('customer_id', $customer_id)->first();
        $cartMenus = $cart->menus()->get();

        foreach ($cartMenus as $cartMenu) {
            $price = ($cartMenu->price) * ($cartMenu->pivot->quantity);
            $totalPrice += $price;
            $menu_id = $cartMenu->id;

            $order->menus()->attach($menu_id, [
                'quantity' => $cartMenu->pivot->quantity,
                'menu_prepare' => 0,
                'menu_serve' => 0,
                'remarks' => $cartMenu->pivot->remarks,
                'sides' => $cartMenu->pivot->sides
            ]);
        }

        return view('ordering.confirmed', ['totalPrice' => $totalPrice]);
    }
}
