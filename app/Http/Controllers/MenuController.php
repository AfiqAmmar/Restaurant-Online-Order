<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('manage.menu.index');
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
            3 => 'availability',
            4 => 'action',
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

        $categories = Category::all();
        $count = 0;
        foreach($categories as $category){
            if($search_arr[0]['value'] == null){
                $menus = Menu::orderBy('id', 'DESC')->skip($start)->take($rowPerPage)->get();
            }
            else if(stripos( "Available", $search_arr[0]['value']) !== false){
                $menus = Menu::where('availability', 'LIKE', 0)
                    ->orderBy('id', 'DESC')->skip($start)->take($rowPerPage)->get();

                $totalFiltered = Menu::where('availability', 'LIKE', 0)
                    ->orderBy('id', 'DESC')->skip($start)->take($rowPerPage)
                    ->count();

                $totalData = $totalFiltered;
            }
            else if(stripos("Unavailable", $search_arr[0]['value']) !== false){
                $menus = Menu::where('availability', 'LIKE', 1)
                    ->orderBy('id', 'DESC')->skip($start)->take($rowPerPage)->get();

                $totalFiltered = Menu::where('availability', 'LIKE', 1)
                    ->orderBy('id', 'DESC')->skip($start)->take($rowPerPage)
                    ->count();

                $totalData = $totalFiltered;
            }
            else if(stripos($category->name, $search_arr[0]['value']) !== false){
                $menus = Menu::where('category_id', 'LIKE', $category->id)
                    ->orderBy('id', 'DESC')->skip($start)->take($rowPerPage)->get();

                $totalFiltered = Menu::where('category_id', 'LIKE', $category->id)
                    ->orderBy('id', 'DESC')->skip($start)->take($rowPerPage)
                    ->count();

                $totalData = $totalFiltered;
                break;
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
        }
        $count = $menus;

        $data = [];
        
        $category = Category::all();
        if(!(empty($category)))
        {
            if(!(empty($menus))){
                foreach($menus as $menu)
                {
                    $editButton = '<div class="btn-group">
                                    <button type="button" class="btn btn-primary"><a href="'. url('menu/' . $menu->id ) .'" class="text-white">View & Edit</a></button>
                                  </div>';
    
                    $category = Category::where('id', $menu->category_id)->get();
                    $availability = "";
                    if($menu->availability == 0){
                        $availability = '<p class="text-success"><strong>Available</strong></p>';
                    }
                    else{
                        $availability = '<p class="text-danger"><strong>Unavailable</strong></p>';
                    }
    
                    $data[] = array(
                        'name' => $menu->name,
                        'category' => $category[0]->name,
                        'price' => $menu->price,
                        'availability' => $availability,
                        'action' => $editButton,
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
        }

        $jsonData = array(
            "draw" => intval($draw),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            "count" => $count,
            "start" => $start,
            "row" => $rowPerPage,
        );

        echo json_encode($jsonData);
    }

    public function addMenuIndex()
    {
        $categories = Category::all();
        return view('manage.menu.add', [
            'categories' => $categories,
        ]);
    }

    public function addMenu(Request $request)
    {

        // validate credentials
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('menus')],
            'description' => ['required', 'string'],
            'category' => ['required'],
            'imageFile' => ['required', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
            'price' => ['required', 'numeric'],
            'sides' => ['required'],
            'time' => ['required', 'numeric'],
            ],
        );

        $imageNameWithExt = $request->file('imageFile')->getClientOriginalName();
        $imageName = pathinfo($imageNameWithExt, PATHINFO_FILENAME);
        $extension = $request->file('imageFile')->getClientOriginalExtension();
        $imageNameToStore = $imageName.'_'.time().'.'.$extension;
        $path = $request->file('imageFile')->move(public_path('menu_img'), $imageNameToStore);

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

        $menuInsert->analyses()->create([
            'orders' => 0,
        ]);

        Session::flash('success','Menu added successfully');
        return response()->json(['success'=>'Menu added successfully']);
    }

    public function viewMenu($id)
    {
        $menu = Menu::where('id', $id)->first();
        $categories = Category::all();
        return view('manage.menu.edit', [
            'menu' => $menu,
            'categories' => $categories,
        ]);
    }

    public function editMenu(Request $request, $id)
    {
        $quantityMenu = 0;
        if($request->quantity < 0 || $request->quantity == null){
            $quantityMenu = null;
        }
        else{
            $quantityMenu = $request->quantity;
        }

        $menuEdit = Menu::find($id);

        if($request->hasFile('imageFile')){
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:255', Rule::unique('menus')->ignore($menuEdit->id)],
                'description' => ['required', 'string'],
                'category' => ['required'],
                'imageFile' => ['required', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
                'price' => ['required', 'numeric'],
                'sides' => ['required'],
                'time' => ['required', 'numeric'],
                'availability' => ['required'],
                'quantity' => ['required', 'numeric', 'nullable'],
                ],
            );

            if(File::exists(public_path('menu_img/' . $menuEdit->image_name))){
                File::delete(public_path('menu_img/' . $menuEdit->image_name));
            }

            $imageNameWithExt = $request->file('imageFile')->getClientOriginalName();
            $imageName = pathinfo($imageNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('imageFile')->getClientOriginalExtension();
            $imageNameToStore = $imageName.'_'.time().'.'.$extension;
            $path = $request->file('imageFile')->move(public_path('menu_img'), $imageNameToStore);

            $menuEdit->update([
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category,
                'image_name' => $imageNameToStore,
                'image_path' => $path,
                'price' => $request->price,
                'sides' => $request->sides,
                'preparation_time' => $request->time,
                'availability' => $request->availability,
                'available_quantity'=> $quantityMenu,
            ]);
        }
        else{
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:255', Rule::unique('menus')->ignore($menuEdit->id)],
                'description' => ['required', 'string'],
                'category' => ['required'],
                'price' => ['required', 'numeric'],
                'sides' => ['required'],
                'time' => ['required', 'numeric'],
                'availability' => ['required'],
                'quantity' => ['required', 'numeric'],
                ],
            );

            $menuEdit->update([
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category,
                'price' => $request->price,
                'sides' => $request->sides,
                'preparation_time' => $request->time,
                'availability' => $request->availability,
                'available_quantity'=> $quantityMenu,
            ]);
        }

        Session::flash('success','Menu updated successfully');
        return response()->json(['success'=>'Menu updated successfully']);
    }

    public function deleteMenu($id)
    {
        $menuDelete = Menu::find($id);
        if(File::exists(public_path('menu_img/' . $menuDelete->image_name))){
            File::delete(public_path('menu_img/' . $menuDelete->image_name));
        }
        $menuDelete->analyses->delete();
        foreach($menuDelete->carts as $cart)
        {
            $cart->pivot->delete();
        }
        foreach($menuDelete->orders as $order)
        {
            $order->pivot->delete();
        }
        $menuDelete->delete();
        Session::flash('success','Menu deleted successfully');
        return redirect('/menu');
    }

    public function analyze()
    {
        $menus_arr = array();
        $categories_arr = array();
        $analyze_data = array();
        $categories = Category::orderBy('category', 'ASC')->get();
        foreach($categories as $category)
        {
            $menu_arr = array();
            array_push($categories_arr, $category->name);
            foreach($category->menus as $menu)
            {
                $menu_info = Menu::find($menu->id);
                $quantity = 0;
                foreach($menu_info->orders as $order)
                {
                    $quantity = $quantity + $order->pivot->quantity;
                }
                $menu_arr[$menu->name] = $quantity;
                array_push($menus_arr, $menu->name);
            }
            $analyze_data[$category->name] = $menu_arr;
        }
        return view('manage.menu.analyze', [
            'categories' => $categories,
            'categories_arr' => $categories_arr,
            'menus_arr' => $menus_arr,
            'analyze_data' => $analyze_data,
        ]);
    }

    public function getFoodRank(Request $request)
    {
        try{
            $search_arr[] = $request->get('search');
        }
        catch (\Exception $e){
            $search_arr[] = '';
        }

        $columns = array(
            0 => 'rank',
            1 => 'name',
            2 => 'orders'
        );

        $draw = $request->get('draw');
        $start = $request->get('start');
        $rowPerPage = $request->get('length');
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');

        $columnIndex = $columnIndex_arr[0]['column'];
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $columnIndex_arr[0]['dir'];

        $foodsCount = Menu::whereRelation('categories', 'category', 0)->count();
        $totalFiltered = $foodsCount;
        $totalData = $totalFiltered;

        $foods = Menu::whereRelation('categories', 'category', 0)->get();

        $data = [];

        $foods_arr = array();

        foreach($foods as $food)
        {
            $foods_arr[$food->name] = 0;
        }

        foreach($foods as $food)
        {
            $quantity = 0;
            foreach($food->orders as $order)
            {
                $quantity = $quantity + $order->pivot->quantity;
            }
            $foods_arr[$food->name] = $quantity;
            
            $food->analyses()->update([
                'orders' => $quantity,
            ]);
        }

        arsort($foods_arr);
        $count = $start + 1;
        $menus_name = array();
        $menus_data = array();
        $index_size = array();

        foreach($foods_arr as $food=>$food_value)
        {
            array_push($menus_name, $food);
            array_push($menus_data, $food_value);
        }

        if(!(empty($foods)))
        {
            if(sizeof($menus_name) % 10 == 0)
            {
                for($i = $start; $i < $start + $rowPerPage; $i++)
                {
                    $data[] = array(
                        'rank' => $count,
                        'name' => $menus_name[$i],
                        'orders' => $menus_data[$i],
                    );
                    array_push($index_size, $i);
                    $count++;

                    $keys = array_column($data, $columnName);
                    if($columnSortOrder == 'asc')
                    {
                        array_multisort($keys, SORT_ASC, $data);
                    } else {
                        array_multisort($keys, SORT_DESC, $data);
                    }
                }
            }
            else
            {   
                $remainder = sizeof($menus_name) % 10;
                $indicator = $totalData / 10;
                $end = $start / 10;
                $indicator = (int) ($totalData / 10);
                if($end == $indicator)
                {
                    for($i = $start; $i < $start + $remainder; $i++)
                    {
                        $data[] = array(
                            'rank' => $count,
                            'name' => $menus_name[$i],
                            'orders' => $menus_data[$i],
                        );
                        array_push($index_size, $i);
                        $count++;

                        $keys = array_column($data, $columnName);
                        if($columnSortOrder == 'asc')
                        {
                            array_multisort($keys, SORT_ASC, $data);
                        } else {
                            array_multisort($keys, SORT_DESC, $data);
                        }
                    }
                }
                else
                {
                    for($i = $start; $i < $start + $rowPerPage; $i++)
                    {
                        $data[] = array(
                            'rank' => $count,
                            'name' => $menus_name[$i],
                            'orders' => $menus_data[$i],
                        );
                        array_push($index_size, $i);
                        $count++;

                        $keys = array_column($data, $columnName);
                        if($columnSortOrder == 'asc')
                        {
                            array_multisort($keys, SORT_ASC, $data);
                        } else {
                            array_multisort($keys, SORT_DESC, $data);
                        }
                    }
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

    public function getBeverageRank(Request $request)
    {
        try{
            $search_arr[] = $request->get('search');
        }
        catch (\Exception $e){
            $search_arr[] = '';
        }

        $columns = array(
            0 => 'rank',
            1 => 'name',
            2 => 'orders'
        );

        $draw = $request->get('draw');
        $start = $request->get('start');
        $rowPerPage = $request->get('length');
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');

        $columnIndex = $columnIndex_arr[0]['column'];
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $columnIndex_arr[0]['dir'];

        $beveragesCount = Menu::whereRelation('categories', 'category', 1)->count();
        $totalFiltered = $beveragesCount;
        $totalData = $totalFiltered;

        $beverages = Menu::whereRelation('categories', 'category', 1)->get();

        $data = [];

        $foods_arr = array();

        foreach($beverages as $beverage)
        {
            $beverages_arr[$beverage->name] = 0;
        }

        foreach($beverages as $beverage)
        {
            $quantity = 0;
            foreach($beverage->orders as $order)
            {
                $quantity = $quantity + $order->pivot->quantity;
            }
            $beverages_arr[$beverage->name] = $quantity;
            
            $beverage->analyses()->update([
                'orders' => $quantity,
            ]);
        }

        arsort($beverages_arr);
        $count = $start + 1;
        $menus_name = array();
        $menus_data = array();
        $index_size = array();

        foreach($beverages_arr as $beverage=>$beverage_value)
        {
            array_push($menus_name, $beverage);
            array_push($menus_data, $beverage_value);
        }

        if(!(empty($beverages)))
        {
            if(sizeof($menus_name) % 10 == 0)
            {
                for($i = $start; $i < $start + $rowPerPage; $i++)
                {
                    $data[] = array(
                        'rank' => $count,
                        'name' => $menus_name[$i],
                        'orders' => $menus_data[$i],
                    );
                    array_push($index_size, $i);
                    $count++;

                    $keys = array_column($data, $columnName);
                    if($columnSortOrder == 'asc')
                    {
                        array_multisort($keys, SORT_ASC, $data);
                    } else {
                        array_multisort($keys, SORT_DESC, $data);
                    }
                }
            }
            else
            {   
                $remainder = sizeof($menus_name) % 10;
                $indicator = $totalData / 10;
                $end = $start / 10;
                $indicator = (int) ($totalData / 10);
                if($end == $indicator)
                {
                    for($i = $start; $i < $start + $remainder; $i++)
                    {
                        $data[] = array(
                            'rank' => $count,
                            'name' => $menus_name[$i],
                            'orders' => $menus_data[$i],
                        );
                        array_push($index_size, $i);
                        $count++;

                        $keys = array_column($data, $columnName);
                        if($columnSortOrder == 'asc')
                        {
                            array_multisort($keys, SORT_ASC, $data);
                        } else {
                            array_multisort($keys, SORT_DESC, $data);
                        }
                    }
                }
                else
                {
                    for($i = $start; $i < $start + $rowPerPage; $i++)
                    {
                        $data[] = array(
                            'rank' => $count,
                            'name' => $menus_name[$i],
                            'orders' => $menus_data[$i],
                        );
                        array_push($index_size, $i);
                        $count++;

                        $keys = array_column($data, $columnName);
                        if($columnSortOrder == 'asc')
                        {
                            array_multisort($keys, SORT_ASC, $data);
                        } else {
                            array_multisort($keys, SORT_DESC, $data);
                        }
                    }
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
