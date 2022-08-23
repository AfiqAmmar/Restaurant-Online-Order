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
        $today_date = date('Y-m-d', strtotime('2022-08-18'));
        $today_arr = array();
        $categories = Category::all();
        $count = 0;
        foreach($categories as $category)
        {
            $today_arr += [$category->name => 0];
        }
        // $today_arr['Appetizers'] = 10 + $today_arr['Appetizers'];
        dd($today_arr);
        $order = Order::whereDate('created_at', $today_date)->get();
        $menu = $order[3]->menus[0]->id;
        $category = Menu::find($menu);
        
        dd($category->categories->id);
        return view('manage.dashboard.index', [
            'test' => $test,
        ]);
    }
}
