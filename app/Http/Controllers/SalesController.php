<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $customers = Customer::all();
        $customers_count = count($customers);
        $regular_customers_count = 0;
        foreach($customers as $customer)
        {
            if(count($customer->orders) > 2)
            {
                $regular_customers_count++;
            }
        }

        $new_orders = Order::where('prepare_status', 0)->count();
        $available_table = 0;
        $tables = Table::all();
        foreach($tables as $table)
        {
            if(sizeof($table->orders) == 0)
            {
                $available_table++;
                continue;
            }
            else
            {
                if($table->orders[sizeof($table->orders)-1]->payment_status == 1)
                {
                    $available_table++;
                }
            }
        }
        
        $today_date = date('Y-m-d');
        // $today_date = date('Y-m-d', strtotime('2022-08-23'));
        $today_arr = array();
        $week_arr = array();
        $month_arr = array();
        $year_arr = array();
        $categories_arr = array();
        $today_data_arr = array();
        $week_data_arr = array();
        $month_data_arr = array();
        $year_data_arr = array();
        $total_today = 0;
        $total_week = 0;
        $total_month = 0;
        $total_year = 0;
        $categories = Category::all();

        foreach($categories as $category)
        {
            $today_arr += [$category->name => 0];
            $week_arr += [$category->name => 0];
            $month_arr += [$category->name => 0];
            $year_arr += [$category->name => 0];
        }

        // daily sales
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

        // weekly sales
        $orders_weeks = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        foreach($orders_weeks as $order_week)
        {
            foreach($order_week->menus as $menu)
            {
                $total = $menu->price * $menu->pivot->quantity;
                $menu_category = Menu::find($menu->id);
                $total_week = $total_week + $total;
                $week_arr[$menu_category->categories->name] = $total + $week_arr[$menu_category->categories->name];
            }
        }

        // monthly sales
        $orders_months = Order::whereMonth('created_at', Carbon::now()->month)->get();
        foreach($orders_months as $order_month)
        {
            foreach($order_month->menus as $menu)
            {
                $total = $menu->price * $menu->pivot->quantity;
                $menu_category = Menu::find($menu->id);
                $total_month = $total_month + $total;
                $month_arr[$menu_category->categories->name] = $total + $month_arr[$menu_category->categories->name];
            }
        }

        // yearly sales
        $orders_years = Order::whereYear('created_at', Carbon::now()->year)->get();
        foreach($orders_years as $order_year)
        {
            foreach($order_year->menus as $menu)
            {
                $total = $menu->price * $menu->pivot->quantity;
                $menu_category = Menu::find($menu->id);
                $total_year = $total_year + $total;
                $year_arr[$menu_category->categories->name] = $total + $year_arr[$menu_category->categories->name];
            }
        }

        foreach($categories as $category)
        {
            array_push($categories_arr, $category->name);
            array_push($today_data_arr, $today_arr[$category->name]);
            array_push($week_data_arr, $week_arr[$category->name]);
            array_push($month_data_arr, $month_arr[$category->name]);
            array_push($year_data_arr, $year_arr[$category->name]);
        }

        return view('manage.dashboard.index', [
            'categories_arr' => $categories_arr,
            'today_data_arr' => $today_data_arr,
            'total_today' => $total_today,
            'week_data_arr' => $week_data_arr,
            'total_week' => $total_week,
            'month_data_arr' => $month_data_arr,
            'total_month' => $total_month,
            'year_data_arr' => $year_data_arr,
            'total_year' => $total_year,
            'customers_count' => $customers_count,
            'regular_customers_count' => $regular_customers_count,
            'new_orders' => $new_orders,
            'available_table' => $available_table,
        ]);
    }
}
