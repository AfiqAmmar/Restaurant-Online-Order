<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;

class OrderingController extends Controller
{
    public function index()
    {
        return view('ordering.index');
    }

    public function getMenus()
    {
        return view('ordering.menus', [
            // 'menus' => Menu::all()->filter(request(['search'])),
            'menus' => Menu::all(),
            'categories' => Category::all(),
        ]);
    }

    public function getMenu(Menu $menu)
    {
        return view('ordering.menu', ['menu' => $menu]);
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
