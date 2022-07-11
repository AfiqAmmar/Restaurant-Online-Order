<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

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

        $jsonData = array(
            "draw" => intval($draw),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            "count" => $count,
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
            'imageFile' => ['required', 'mimes:jpg,jpeg,png,svg', 'image', 'max:2048'],
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

        Session::flash('success','Menu added successfully');
        return response()->json(['success'=>'Menu added successfully']);
    }

    public function viewMenu($id)
    {
        $menu = Menu::where('id', $id)->first();
        $categories = Category::all();
        return view('menu.edit', [
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
                'name' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string'],
                'category' => ['required'],
                'imageFile' => ['required', 'mimes:jpg,jpeg,png,svg', 'image', 'max:2048'],
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
                'name' => ['required', 'string', 'max:255'],
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
        $menuDelete->delete();
        Session::flash('success','Menu deleted successfully');
        return redirect('/menu');
    }
}