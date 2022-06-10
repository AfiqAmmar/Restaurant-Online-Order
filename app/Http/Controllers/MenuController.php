<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Support\Facades\Session;

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
                    'category' => $category[0]->name,
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

    public function addMenuIndex()
    {
        $categories = Category::all();
        return view('menu.add', [
            'categories' => $categories,
        ]);
    }

    public function addMenu(Request $request)
    {
        
        // validate credentials
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['required'],
            'imageFile' => ['required', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
            'price' => ['required', 'numeric'],
            'sides' => ['required'],
            'time' => ['required', 'numeric'],
            ],
        );

        $imageNameWithExt = $request->file('imageFile')->getClientOriginalName();
        $imageName = pathinfo($imageNameWithExt, PATHINFO_FILENAME);
        $extension = $request->file('imageFile')->getClientOriginalExtension();
        $imageNameToStore = $imageName.'_'.time().'.'.$extension;
        $path = $request->file('imageFile')->move(public_path('menu_img'), $imageName);

        // insert data
        $menuInsert = Menu::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category,
            'image_name' => $imageNameToStore,
            'image_path' => $path,
            'price' => $request->price,
            'sides' => $request->sides,
            'preparation_time' => $request->time,
        ]);

        Session::flash('success','Menu added successfully');
        return response()->json(['success'=>'Menu added successfully']);
    }
}
