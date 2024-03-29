<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use App\Models\Analysis;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;

use App\Events\MessageNotification;
use function PHPUnit\Framework\isEmpty;

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
            'phone_num' => ['required', 'min_digits:10'],
            'table_number' => ['required', 'numeric'],
        ]);

        $phone_num = $validatedData['phone_num'];
        $table_number = $validatedData['table_number'];
        $customer_id = Customer::firstOrCreate(['phone_num' => $phone_num])->id;

        $this->checkIfCartExists($customer_id);
        $this->checkIfOrderExists($customer_id);
        $this->createCartAndOrder($customer_id, $table_number);

        return response()->json(['customer_id' => $customer_id]);
    }

    public function checkIfCartExists($customer_id)
    {
        $carts = Cart::where('customer_id', $customer_id)->get();

        if ($carts->isNotEmpty()) {
            foreach ($carts as $cart) {
                $this->returnCartMenusToDatabase($cart);
                $cart_id = $cart->id;
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

    public function getMenus($customer_id, Request $request)
    {
        // favourite menu $fav_menu_col and menu recommendation $recommend_menu_col
        // create User Matrix = $user_mtrx
        $customer = Customer::find($customer_id);
        $user_mtrx = array();
        if ($customer->orders[0]->menus->isNotEmpty()) {
            foreach ($customer->orders as $order) {
                foreach ($order->menus as $menu) {
                    if (array_key_exists($menu->name, $user_mtrx)) {
                        $user_mtrx[$menu->name] = $menu->pivot->quantity + $user_mtrx[$menu->name];
                    } else {
                        $user_mtrx[$menu->name] = $menu->pivot->quantity;
                    }
                }
            }
            $sides_app_arr = array(
                Category::where('name', "Appetizers")->first()->id,
                Category::where('name', "Sides")->first()->id
            );
            $categories = Category::where('category', 0)
                ->whereNotIn('id', $sides_app_arr)->get();
            $user_mtrx_data = array();
            $fav_menu_arr = $user_mtrx;
            foreach ($user_mtrx as $menu => $menu_value) {
                if (
                    Menu::where('name', $menu)->first()->categories->category == 1
                    || Menu::where('name', $menu)->first()->categories->name == "Appetizers"
                    || Menu::where('name', $menu)->first()->categories->name == "Sides"
                ) {
                    unset($user_mtrx[$menu]);
                } else {
                    array_push($user_mtrx_data, $menu_value);
                }
            }

            // finding favourite menu
            if (sizeof($user_mtrx) > 1) {
                foreach ($fav_menu_arr as $menu => $menu_value) {
                    if (
                        Menu::where('name', $menu)->first()->categories->category == 1
                        || Menu::where('name', $menu)->first()->categories->name == "Sides"
                        || Menu::where('name', $menu)->first()->availability == 1
                    ) {
                        unset($fav_menu_arr[$menu]);
                    }
                }
                arsort($fav_menu_arr);
                // dd($fav_menu_arr);
                $fav_menu_arr_keys = array_keys($fav_menu_arr);
                $fav_menu_1 = Menu::where('name', $fav_menu_arr_keys[0])->first();
                $fav_menu_2 = Menu::where('name', $fav_menu_arr_keys[1])->first();
                $fav_menu_col = collect([$fav_menu_1, $fav_menu_2]);
                $fav_menus_id = array($fav_menu_1->id, $fav_menu_2->id);
            } else {
                $fav_menu_col = collect();
                $fav_menus_id = array();
            }

            $categories_arr = array();
            foreach ($categories as $category) {
                array_push($categories_arr, $category->name);
            }

            // create Weighted Menu Matrix = $weighted_menu_mtrx
            $weighted_menu_mtrx = array();
            foreach ($user_mtrx as $menu => $menu_value) {
                $rows_arr = array();
                for ($j = 0; $j < sizeof($categories); $j++) {
                    if (Menu::where('name', $menu)->first()->categories->name == $categories[$j]->name) {
                        array_push($rows_arr, 1);
                    } else {
                        array_push($rows_arr, 0);
                    }
                }
                array_push($weighted_menu_mtrx, $rows_arr);
            }

            // multiply UM and WMM to create User Profile Matrix = $user_profile_mtrx
            $um_wmm_res = array();
            for ($i = 0; $i < sizeof($user_mtrx_data); $i++) {
                $um_wmm_res_row = array();
                for ($j = 0; $j < sizeof($categories); $j++) {
                    array_push($um_wmm_res_row, $user_mtrx_data[$i] * $weighted_menu_mtrx[$i][$j]);
                }
                array_push($um_wmm_res, $um_wmm_res_row);
            }
            $user_profile_mtrx = array();
            $user_profile_mtrx_ctgr = array();
            for ($i = 0; $i < sizeof($categories); $i++) {
                $value = 0;
                for ($j = 0; $j < sizeof($user_mtrx_data); $j++) {
                    $value = $value + $um_wmm_res[$j][$i];
                }
                array_push($user_profile_mtrx, $value);
                $user_profile_mtrx_ctgr[$categories[$i]->name] = $value;
            }

            // sort the UPM and get the highest number
            arsort($user_profile_mtrx);
            arsort($user_profile_mtrx_ctgr);
            $user_profile_mtrx_ctgr_keys = array_keys($user_profile_mtrx_ctgr);

            // Menu Matrix
            $menu_matrix = array();
            $menu_weight = $user_profile_mtrx[0];
            if (sizeof($user_profile_mtrx) == 1) {
                $ctgrys = Category::where('name', $user_profile_mtrx_ctgr_keys[0])
                    ->first()->menus->whereNotIn('id', $fav_menus_id);
                foreach ($ctgrys as $ctgry) {
                    $menu = Menu::find($ctgry->id);
                    $quantity = 0;
                    foreach ($menu->orders as $order) {
                        $quantity = $quantity + $order->pivot->quantity;
                    }
                    $menu_matrix[$ctgry->name] = $quantity;
                }
            } else if ($user_profile_mtrx[0] !== $user_profile_mtrx[1]) {
                $ctgrys = Category::where('name', $user_profile_mtrx_ctgr_keys[0])
                    ->first()->menus->whereNotIn('id', $fav_menus_id);
                foreach ($ctgrys as $ctgry) {
                    $menu = Menu::find($ctgry->id);
                    $quantity = 0;
                    foreach ($menu->orders as $order) {
                        $quantity = $quantity + $order->pivot->quantity;
                    }
                    $menu_matrix[$ctgry->name] = $quantity;
                }
            } else {
                $ctgrys_chosen = array();
                array_push($ctgrys_chosen, $user_profile_mtrx_ctgr_keys[0]);
                array_push($ctgrys_chosen, $user_profile_mtrx_ctgr_keys[1]);
                for ($i = 1; $i < sizeof($user_profile_mtrx) - 1; $i++) {
                    if ($user_profile_mtrx[$i] !== $user_profile_mtrx[$i + 1]) {
                        break;
                    } else {
                        array_push($ctgrys_chosen, $user_profile_mtrx_ctgr_keys[$i + 1]);
                    }
                }
                for ($i = 0; $i < sizeof($ctgrys_chosen); $i++) {
                    $ctgrys = Category::where('name', $ctgrys_chosen[$i])
                        ->first()->menus->whereNotIn('id', $fav_menus_id);
                    foreach ($ctgrys as $ctgry) {
                        $menu = Menu::find($ctgry->id);
                        $quantity = 0;
                        foreach ($menu->orders as $order) {
                            $quantity = $quantity + $order->pivot->quantity;
                        }
                        $menu_matrix[$ctgry->name] = $quantity;
                    }
                }
            }

            foreach ($menu_matrix as $menu => $menu_value) {
                if (Menu::where('name', $menu)->first()->availability == 1) {
                    unset($menu_matrix[$menu]);
                }
            }

            // scalar multiplication of MM and $menu_weight
            foreach ($menu_matrix as $menu => $menu_value) {
                $menu_matrix[$menu] = $menu_value * $menu_weight;
            }
            arsort($menu_matrix);
            $menu_matrix_keys = array_keys($menu_matrix);
            $recommend_menu_1 = Menu::where('name', $menu_matrix_keys[0])->first();
            $recommend_menu_2 = Menu::where('name', $menu_matrix_keys[1])->first();
            $recommend_menu_col = collect([$recommend_menu_1, $recommend_menu_2]);
            // dd($recommend_menu_col->isNotEmpty());
        } else {
            $recommend_menu_col = collect();
            $fav_menu_col = collect();
        }

        // trending menus $trend_menus
        $trend_menus_rank = Analysis::orderByDesc('orders')->take(10)->get();
        $trend_menus_arr = array();
        foreach ($trend_menus_rank as $trend_menu) {
            if (sizeof($trend_menus_arr) == 4) {
                break;
            } else {
                if (
                    $trend_menu->menus->availability == 1
                    || $trend_menu->menus->categories->name == "Sides"
                    || $trend_menu->menus->categories->category == 1
                ) {
                    continue;
                } else {
                    array_push($trend_menus_arr, $trend_menu->menus);
                }
            }
        }
        $trend_menus = collect($trend_menus_arr);

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
            'cart_menus' => $cartMenus,
            'totalPrice' => $totalPrice,
            'recommend_menu_col' => $recommend_menu_col,
            'fav_menu_col' => $fav_menu_col,
            'trend_menus' => $trend_menus,
            'request' => $request
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

        if (!is_null($available_quantity)) {
            $remaining_quantity = $available_quantity - $quantity;
            $menu->update(['available_quantity' => $remaining_quantity]);
        }

        return redirect('/' . $customer_id . '/menus');
    }

    public function getCart($customer_id)
    {
        $totalPrice = 0;
        $cartFoods = array();
        $cartDrinks = array();
        $cart = Cart::where('customer_id', $customer_id)->first();
        $cartMenus = $cart->menus()->get();

        if ($cartMenus->isNotEmpty()) {
            foreach ($cartMenus as $cartMenu) {
                $category = Category::where('id', $cartMenu->category_id)->first();
                $isDrink = $category->category;

                if ($isDrink) {
                    array_push($cartDrinks, $cartMenu);
                } else {
                    array_push($cartFoods, $cartMenu);
                }

                $price = ($cartMenu->price) * ($cartMenu->pivot->quantity);
                $totalPrice += $price;
            }
        }

        $estimatedTimeFoods = ($cartFoods) ? $this->calcEstOrderPrepTime($cartFoods, 0) : 0;
        $estimatedTimeDrinks = ($cartDrinks) ? $this->calcEstOrderPrepTime($cartDrinks, 1) : 0;

        return view('ordering.confirm', [
            'menus' => Menu::all(),
            'cart_id' => $cart->id,
            'customer_id' => $customer_id,
            'cartMenus' => $cartMenus,
            'totalPrice' => $totalPrice,
            'estimatedTimeFoods' => $estimatedTimeFoods,
            'estimatedTimeDrinks' => $estimatedTimeDrinks,
        ]);
    }

    public function updateCartMenuQuantity(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $menu_id = $request->input('menu_id');
        $quantity = $request->input('quantity');
        $isPlus = $request->input('isPlus');

        $menu = Menu::where('id', $menu_id)->first();
        $available_quantity = $menu->available_quantity;

        if (!is_null($available_quantity)) {
            $remaining_quantity = ($isPlus)
                ? $available_quantity - 1
                : $available_quantity + 1;
            $menu->update(['available_quantity' => $remaining_quantity]);
        }

        $cart = Cart::where('customer_id', $customer_id)->first();
        $cart->menus()->updateExistingPivot($menu_id, ['quantity' => $quantity]);

        $cartMenus = $cart->menus()->get();
        $totalPrice = 0;
        foreach ($cartMenus as $cartMenu) {
            $price = ($cartMenu->price) * ($cartMenu->pivot->quantity);
            $totalPrice += $price;
        }

        return response()->json(['totalPrice' => $totalPrice]);
    }

    public function deleteMenuFromCart($customer_id, $menu_id)
    {
        $cart = Cart::where('customer_id', $customer_id)->first();
        $this->returnCartMenusToDatabase($cart, $menu_id);
        return redirect('/' . $customer_id . '/cart');
    }

    public function clearCart($customer_id)
    {
        $cart = Cart::where('customer_id', $customer_id)->first();
        $this->returnCartMenusToDatabase($cart);
        return redirect('/' . $customer_id . '/menus');
    }

    public function confirmOrder($customer_id)
    {
        $totalPrice = 0;
        $cartFoods = array();
        $cartDrinks = array();
        $order = Order::where('customer_id', $customer_id)->get()->last();
        $cart = Cart::where('customer_id', $customer_id)->first();
        $cartMenus = $cart->menus()->get();

        foreach ($cartMenus as $cartMenu) {
            $category = Category::where('id', $cartMenu->category_id)->first();
            $isDrink = $category->category;
            if ($isDrink) {
                array_push($cartDrinks, $cartMenu);
            } else {
                array_push($cartFoods, $cartMenu);
            }

            $price = ($cartMenu->price) * ($cartMenu->pivot->quantity);
            $totalPrice += $price;

            $available_quantity = $cartMenu->available_quantity;
            if ($available_quantity === 0) {
                $cartMenu->update([
                    'availability' => 1,
                    'available_quantity' => null
                ]);
            }

            $order->menus()->attach($cartMenu->id, [
                'quantity' => $cartMenu->pivot->quantity,
                'menu_prepare' => 0,
                'menu_serve' => 0,
                'remarks' => $cartMenu->pivot->remarks,
                'sides' => $cartMenu->pivot->sides
            ]);
        }

        $estimatedTimeFoods = $this->calcEstOrderPrepTime($cartFoods, 0);
        $estimatedTimeDrinks = $this->calcEstOrderPrepTime($cartDrinks, 1);
        $order->update([
            'estimate_time' => $estimatedTimeFoods,
            'estimate_time_drink' => $estimatedTimeDrinks,
            'order_confirmed' => 1
        ]);

        event(new MessageNotification('Order confirmed!'));
        return view('ordering.confirmed', [
            'cartMenus' => $cartMenus,
            'totalPrice' => $totalPrice,
            'estimatedTimeFoods' => $estimatedTimeFoods,
            'estimatedTimeDrinks' => $estimatedTimeDrinks,
        ]);
    }

    public function returnCartMenusToDatabase($cart, $menu_id = null)
    {
        $cartMenus = $cart->menus();

        if ($menu_id) {
            $cartMenu = $cartMenus->get()
                ->where('id', $menu_id)->first();
            $this->returnCartMenuQuantityToDatabase($cartMenu);
            $cartMenus->detach($menu_id);
        } else {
            if ($cartMenus->get()->isNotEmpty()) {
                foreach ($cartMenus->get() as $cartMenu) {
                    $this->returnCartMenuQuantityToDatabase($cartMenu);
                }
                $cartMenus->detach();
            }
        }
    }

    public function returnCartMenuQuantityToDatabase($cartMenu)
    {
        $available_quantity = $cartMenu->available_quantity;
        $cartMenu_quantity = $cartMenu->pivot->quantity;

        if (!is_null($available_quantity)) {
            $remaining_quantity = $available_quantity + $cartMenu_quantity;
            $cartMenu->update(['available_quantity' => $remaining_quantity]);
        }
    }

    public function calcEstOrderPrepTime($cartMenus, $isDrink)
    {
        // sort menus by ascending category and descending prep time
        $menus = $this->sortMenusByAscCategoryAndDescPrepTime($cartMenus);

        // get orders from database
        $orders = Order::where([
            ['estimate_time', '!=', 0], ['order_confirmed', 1]
        ])->latest()->get();

        // input is first order
        if ($orders->isEmpty()) {
            $finalMenus = $this->deductEverySecondMenuInEachCategory($menus);
            $estimatedTime = $this->getHighestEstTimeFromEachCategory($finalMenus);
        }

        // previous orders already exist
        else {
            // check if menu exists in previous 5 orders
            $orderCount = ($orders->count() < 5) ? $orders->count() : 5;
            for ($i = 0; $i < $orderCount; $i++) {
                $orderMenus = $orders[$i]->menus;
                foreach ($orderMenus as $orderMenu) {
                    foreach ($menus as $menu_id => $menu) {
                        if ($menu_id == $orderMenu->id) {
                            unset($menus[$menu_id]);
                        }
                    }
                }
            }

            $orderEstTime = ($isDrink)
                ? $orders->first()->estimate_time_drink
                : $orders->first()->estimate_time;

            if ($menus) {
                $finalMenus = $this->deductEverySecondMenuInEachCategory($menus);

                // add first menu's est time and add it to prev order's est time
                $estimatedTime = $this->getHighestEstTimeFromEachCategory($finalMenus);
                $estimatedTime += $orderEstTime;
            } else {
                // use previous order's estimated time
                $estimatedTime = $orderEstTime;
            }
        }

        return $estimatedTime;
    }


    public function sortMenusByAscCategoryAndDescPrepTime($cartMenus)
    {
        // add menus in cart into array with category id and est time as values
        $menus = array();
        foreach ($cartMenus as $cartMenu) {
            $category_id = $cartMenu->category_id;
            $prepTime = $cartMenu->preparation_time;
            $menus[$cartMenu->id] = array($category_id, $prepTime);
        }

        // sort menus in cart by asc category id and desc prep time
        uasort($menus, function ($menu1, $menu2) {
            return ($menu1[0] == $menu2[0])
                ? $menu2[1] <=> $menu1[1]
                : $menu1[0] <=> $menu2[0];
        });

        return $menus;
    }

    public function deductEverySecondMenuInEachCategory($menus)
    {
        $menusLength = count($menus) - 2;
        $index = 1;
        $finalMenus = array();

        // add first menu to finalMenus array
        array_push($finalMenus, current($menus));
        next($menus);

        for ($i = 0; $i < $menusLength; ++$i) {
            $currentMenu = current($menus)[0];
            $nextMenu = next($menus) ? current($menus)[0] : null;

            // deduct every second menu in each category
            if ($index % 2 == 0) {
                array_push($finalMenus, prev($menus));
                next($menus);
            }

            // reset index to 0 if next menu is from different category
            if ($currentMenu != $nextMenu) {
                $index = 0;
            }
            // increment index to 1 if next menu is from same category
            else {
                $index++;
            }
        }

        return $finalMenus;
    }

    public function getHighestEstTimeFromEachCategory($menus)
    {
        // add menu with highest est time from each category to finalMenus
        $finalMenus = array();
        array_push($finalMenus, current($menus));
        $menusLength = count($menus) - 1;
        for ($i = 0; $i < $menusLength; ++$i) {
            if (value(current($menus)[0]) != value(next($menus))[0]) {
                array_push($finalMenus, current($menus));
            }
        }

        // resort finalMenus and set pointer to first menu
        uasort($finalMenus, function ($menu1, $menu2) {
            return $menu2[1] <=> $menu1[1];
        });
        reset($finalMenus);

        // return zero if no menus
        $estimatedTime = current($finalMenus)[1] ?? 0;
        return $estimatedTime;
    }
}
