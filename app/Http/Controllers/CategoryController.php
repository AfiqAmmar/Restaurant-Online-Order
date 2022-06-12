<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('category.index');
    }

    public function getCategories(Request $request)
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
            2 => 'action'
        );

        $draw = $request->get('draw');
        $start = $request->get('start');
        $rowPerPage = $request->get('length');
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');

        $columnIndex = $columnIndex_arr[0]['column'];
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $columnIndex_arr[0]['dir'];

        $categoriesCount = Category::count();
        $totalFiltered = $categoriesCount;
        $totalData = $totalFiltered;
        
        if($search_arr[0]['value'] == null){
            $categories = Category::orderBy('id', 'DESC')->skip($start)->take($rowPerPage)->get();
        }
        else if(stripos( "Food", $search_arr[0]['value']) !== false){
            $categories = Category::where('category', 'LIKE', 0)
                ->orderBy('id', 'DESC')->skip($start)->take($rowPerPage)->get();

            $totalFiltered = Category::where('category', 'LIKE', 0)
                ->orderBy('id', 'DESC')->skip($start)->take($rowPerPage)
                ->count();

            $totalData = $totalFiltered;
        }
        else if(stripos("Drinks", $search_arr[0]['value']) !== false){
            $categories = Category::where('category', 'LIKE', 1)
                ->orderBy('id', 'DESC')->skip($start)->take($rowPerPage)->get();

            $totalFiltered = Category::where('category', 'LIKE', 1)
                ->orderBy('id', 'DESC')->skip($start)->take($rowPerPage)
                ->count();

            $totalData = $totalFiltered;
        } 
        else{
            $categories = Category::where('name', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orWhere('category', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orderBy('id', 'DESC')->skip($start)->take($rowPerPage)->get();

            $totalFiltered = Category::where('name', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orWhere('category', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orderBy('id', 'DESC')->skip($start)->take($rowPerPage)
                ->count();

            $totalData = $totalFiltered;
        }

        $data = [];

        if(!(empty($categories))){
            foreach($categories as $category)
            {
                $editButton = '<div class="btn-group">
                                <button type="button" class="btn btn-primary"><a href="'. url('category/' . $category->id ) .'" class="text-white">Edit</a></button>
                              </div>';
                
                $categoryName = "";
                if($category->category == 0){
                    $categoryName = "Food";
                }
                else{
                    $categoryName = "Drinks";
                }
                
                $data[] = array(
                    'name' => $category->name,
                    'category' => $categoryName,
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

    public function addCategoryIndex()
    {
        return view('category.add');
    }

    public function addCategory(Request $request)
    {
        // validate credentials
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required'],
            ],
        );

        // insert data
        $categoryInsert = Category::create([
            'name' => $request->name,
            'category' => $request->category,
        ]);

        Session::flash('success','Menu Category added successfully');
        return response()->json(['success'=>'Menu Category added successfully']);
    }

    public function viewCategory($id)
    {
        $category = Category::where('id', $id)->first();

        return view('category.edit', [
            'category' => $category,
        ]);
    }

    public function editCategory(Request $request, $id)
    {
        // validate credentials
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required'],
            ],
        );

        // update data
        $category = Category::find($id);
        $category->update([
            'name' => $request->name,
            'category' => $request->category,
        ]);

        Session::flash('success','Menu Category updated successfully');
        return response()->json(['success'=>'Menu Category updated successfully']);
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);
        $category->delete();
        Session::flash('success','Menu Category deleted successfully');
        return redirect('/category');
    }
}
