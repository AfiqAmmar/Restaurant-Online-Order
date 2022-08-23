<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // $today_date = date('Y-m-d');
        $today_date = date('Y-m-d', strtotime('2022-08-23'));
        $today_arr = array();
        $categories_arr = array();
        $today_data_arr = array();
        $total_today = 0;
        $categories = Category::all();

        foreach($categories as $category)
        {
            $today_arr += [$category->name => 0];
        }

        $orders = Order::whereDate('created_at', $today_date)->get();

        foreach($orders as $order)
        {
            foreach($order->menus as $menu)
            {
                $total = $menu->price * $menu->pivot->quantity;
                $menu_category = Menu::find($menu->id);
                $total_today = $total_today + $total;
                $today_arr[$menu_category->categories->name] = $total + $today_arr[$menu_category->categories->name];
            }
        }

        foreach($categories as $category)
        {
            array_push($categories_arr, $category->name);
            array_push($today_data_arr, $today_arr[$category->name]);
        }

        return view('manage.dashboard.index', [
            'categories_arr' => $categories_arr,
            'today_data_arr' => $today_data_arr,
            'total_today' => $total_today,
        ]);
    }
}
