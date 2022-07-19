<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;

class OrderingController extends Controller
{
    public function index()
    {
        return view('ordering.index');
    }

    public function storePhoneNumber(Request $request)
    {
        $validatedData = $request->validate([
            'phone_num' => ['required', 'numeric', 'min:10']
        ]);
        $customerData = Customer::where('phone_num', $validatedData)->get();

        if ($customerData->isEmpty()) {
            $customer_id = Customer::create($validatedData)->id;
        } else {
            $customer_id = Customer::where('phone_num', $validatedData)
                ->get('id')[0]->id;
        }

        return redirect('/' . $customer_id . '/menus');
    }

    public function getMenus($id)
    {
        return view('ordering.menus', [
            'id' => $id,
            // 'menus' => Menu::all()->filter(request(['search'])),
            'menus' => Menu::all(),
            'categories' => Category::all(),
        ]);
    }

    public function getMenu($id, Menu $menu)
    {
        return view('ordering.menu', [
            'id' => $id,
            'menu' => $menu,
            'sides' => Menu::all()->where('category_id', '5')
        ]);
    }

    public function addMenuToCart(Request $request, $id)
    {
    }

    public function confirmOrder()
    {
        return view('ordering.confirm');
    }

    public function orderConfirmed()
    {
        return view('ordering.confirmed');
    }
}
