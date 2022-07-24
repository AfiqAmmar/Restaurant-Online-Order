<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Menu;
use App\Models\Menu_Order;
use App\Models\Order;
use App\Models\Table;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Cart_Menu;
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
        $customer_id = $this->storePhoneNumber($phone_num);

        $this->checkIfCartExists($customer_id);
        $this->checkIfOrderExists($customer_id);
        $this->createCartAndOrder($customer_id, $table_number);

        return redirect('/' . $customer_id . '/menus');
    }

    public function storePhoneNumber($phone_num)
    {
        $customerData = Customer::where('phone_num', $phone_num)->get();
        if ($customerData->isEmpty()) {
            $customer_id = Customer::create(['phone_num' => $phone_num])->id;
        } else {
            $customer_id = Customer::where('phone_num', $phone_num)
                ->get()->first()->id;
        }
        return $customer_id;
    }

    public function checkIfCartExists($customer_id)
    {
        $cart = Cart::where('customer_id', $customer_id)->get();

        if ($cart->isNotEmpty()) {
            $cart_id = $cart->first()->id;
            $cartMenus = Cart_Menu::where('cart_id', $cart_id)->get();
            if ($cartMenus->isNotEmpty()) {
                foreach ($cartMenus as $cartMenu) {
                    $cartMenu_id = $cartMenu->first()->id;
                    Cart_Menu::destroy($cartMenu_id);
                }
            }
            Cart::destroy($cart_id);
        }
    }

    public function checkIfOrderExists($customer_id)
    {
        $order = Order::where('customer_id', $customer_id)->get();
        if ($order->isNotEmpty()) {
            $order_id = $order->first()->id;
            $menuOrder = Menu_Order::where('order_id', $order_id)->get();
            if ($menuOrder->isEmpty()) {
                Order::destroy($order_id);
            }
        }
    }

    public function createCartAndOrder($customer_id, $table_number)
    {
        $table_id = Table::where('table_number', $table_number)
            ->get()->first()->id;

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
        $cart_id = Cart::where('customer_id', $customer_id)->get()->first()->id;
        $cartMenus = Cart_Menu::where('cart_id', $cart_id)->get();

        if ($cartMenus->isNotEmpty()) {
            foreach ($cartMenus as $cartMenu) {
                $menu_id = $cartMenu->menu_id;
                $menu = Menu::where('id', $menu_id)->get()->first();
                $price = ($menu->price) * ($cartMenu->quantity);
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
        return view('ordering.menu', [
            'customer_id' => $customer_id,
            'menu' => $menu,
            'sides' => Menu::where('category_id', '3')->get()
        ]);
    }

    // sides not yet finished
    public function addMenuToCart(Request $request, $customer_id, $menu_id)
    {
        $validatedData = $request->validate([
            'quantity' => ['required', 'numeric', 'min:1'],
            'remarks' => 'string',
            // 'sides' => 'required'
        ]);
        // dd($validatedData);

        $cart_id = Cart::where('customer_id', $customer_id)->get()->first()->id;
        $menuToCartData = array(
            'cart_id' => $cart_id,
            'menu_id' => $menu_id,
            'quantity' => $validatedData['quantity'],
            'remarks' => $validatedData['remarks'],
            // 'sides' => $validatedData['sides']
        );

        // if ($validatedData->contains()) {
        //     # code...
        // }
        // array_push($validatedData, $cart_id, $menu_id);
        Cart_Menu::create($menuToCartData);
        return redirect('/' . $customer_id . '/menus');
    }

    public function confirmOrder($customer_id)
    {
        $totalPrice = 0;
        $cart_id = Cart::where('customer_id', $customer_id)->get()->first()->id;
        $cartMenus = Cart_Menu::where('cart_id', $cart_id)->get();

        foreach ($cartMenus as $cartMenu) {
            $menu_id = $cartMenu->menu_id;
            $menu = Menu::where('id', $menu_id)->get()->first();
            $price = ($menu->price) * ($cartMenu->quantity);
            $totalPrice += $price;
        }

        return view('ordering.confirm', [
            'cart_id' => $cart_id,
            'customer_id' => $customer_id,
            'cartMenus' => $cartMenus,
            'menus' => Menu::all(),
            'totalPrice' => $totalPrice
        ]);
    }

    public function deleteMenuFromCart($customer_id, $menu_id)
    {
        $cart_id = Cart::where('customer_id', $customer_id)->get()->first()->id;
        $cartMenus = Cart_Menu::where('cart_id', $cart_id)->get();
        $cartMenu = $cartMenus->where('menu_id', $menu_id);
        $cartMenu_id = $cartMenu->first()->id;
        Cart_Menu::destroy($cartMenu_id);

        return redirect('/' . $customer_id . '/cart/confirm');
    }

    public function clearCart($customer_id)
    {
        $cart_id = Cart::where('customer_id', $customer_id)->get()->first()->id;
        $cartMenus = Cart_Menu::where('cart_id', $cart_id)->get();
        if ($cartMenus->isNotEmpty()) {
            foreach ($cartMenus as $cartMenu) {
                $cartMenu_id = $cartMenu->first()->id;
                Cart_Menu::destroy($cartMenu_id);
            }
        }

        return redirect('/' . $customer_id . '/menus');
    }

    // sides not yet finished
    public function orderConfirmed($customer_id)
    {
        $totalPrice = 0;
        $order_id = Order::where('customer_id', $customer_id)->get()->first()->id;
        $cart_id = Cart::where('customer_id', $customer_id)->get()->first()->id;
        $cartMenus = Cart_Menu::where('cart_id', $cart_id)->get();

        foreach ($cartMenus as $cartMenu) {
            $menu_id = $cartMenu->menu_id;
            $menu = Menu::where('id', $menu_id)->get()->first();
            $price = ($menu->price) * ($cartMenu->quantity);
            $totalPrice += $price;

            $quantity = $cartMenu->quantity;
            $remarks = $cartMenu->remarks;
            // $sides = $cartMenu->sides;

            Menu_Order::create([
                'order_id' => $order_id,
                'menu_id' => $menu_id,
                'quantity' => $quantity,
                'menu_prepare' => 0,
                'menu_serve' => 0,
                'remarks' => $remarks,
                // 'sides' => $sides
            ]);
        }

        return view('ordering.confirmed', ['totalPrice' => $totalPrice]);
    }
}
