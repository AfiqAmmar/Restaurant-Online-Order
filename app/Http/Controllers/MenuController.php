<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('menu.index');
    }

    public function getMenus(Request $request)
    {
        try{
            $search_arr[] = $request->get('search');
        }
        catch (\Exception $e){
            $search_arr[] = '';
        }

        $columns = array(
            0 => 'name',
            1 => 'category',
            2 => 'price',
            3 => 'image',
            4 => 'action'
        );

        $draw = $request->get('draw');
        $start = $request->get('start');
        $rowPerPage = $request->get('length');
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');

        $columnIndex = $columnIndex_arr[0]['column'];
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $columnIndex_arr[0]['dir'];

        $menusCount = Menu::count();
        $totalFiltered = $menusCount;
        $totalData = $totalFiltered;

        if($search_arr[0]['value'] == null){
            $menus = Menu::orderBy('id', 'DESC')->skip($start)->take($rowPerPage)->get();
        }
        else{
            $menus = Menu::where('name', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orWhere('price', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orderBy('id', 'DESC')->skip($start)->take($rowPerPage)->get();

            $totalFiltered = Menu::where('name', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orWhere('price', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orderBy('id', 'DESC')->skip($start)->take($rowPerPage)->get()
                ->count();

            $totalData = $totalFiltered;
        }

        $data = [];

        if(!(empty($menus))){
            foreach($menus as $menu)
            {
                $editButton = '<div class="btn-group">
                                <button type="button" class="btn btn-primary"><a href="'. url('menu/' . $menu->id ) .'" class="text-white">Edit</a></button>
                              </div>';
                
                $category = Category::where('id', $menu->category_id)->get();

                $data[] = array(
                    'name' => $menu->name,
                    'category' => $category->name,
                    'price' => $menu->price,
                    'image' => 'image',
                    'action' => $editButton
                );

                $keys = array_column($data, $columnName);
                if($columnSortOrder == 'asc')
                {
                    array_multisort($keys, SORT_ASC, $data);
                } else {
                    array_multisort($keys, SORT_DESC, $data);
                }
            }
        }

        $jsonData = array(
            "draw" => intval($draw),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );

        echo json_encode($jsonData);
    }
}
