<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class OrderingController extends Controller
{
    public function index()
    {
        return view('ordering.index');
    }

    public function getMenus()
    {
        return view('ordering.menus');
    }

    public function getMenu(Menu $menu)
    {
        return view('ordering.menu');
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
