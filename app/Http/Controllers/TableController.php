<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class TableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('manage.table.index');
    }

    public function getTables(Request $request)
    {
        try{
            $search_arr[] = $request->get('search');
        }
        catch (\Exception $e){
            $search_arr[] = '';
        }

        $columns = array(
            0 => 'table_number',
            1 => 'capacity',
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

        $tablesCount = Table::count();
        $totalFiltered = $tablesCount;
        $totalData = $totalFiltered;

        if($search_arr[0]['value'] == null){
            $tables = Table::orderBy('id', 'DESC')->skip($start)->take($rowPerPage)->get();
        }
        else{
            $tables = Table::where('table_number', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orWhere('seats', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orderBy('id', 'DESC')->skip($start)->take($rowPerPage)->get();

            $totalFiltered = Table::where('table_number', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orWhere('seats', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orderBy('id', 'DESC')->skip($start)->take($rowPerPage)
                ->count();

            $totalData = $totalFiltered;
        }

        $data = [];

        if(!(empty($tables))){
            foreach($tables as $table)
            {
                $editButton = '<div class="btn-group">
                                <button type="button" class="btn btn-primary"><a href="'. url('table/' . $table->id ) .'" class="text-white">Edit</a></button>
                              </div>';

                $data[] = array(
                    'table_number' => $table->table_number,
                    'capacity' => $table->seats,
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

    public function addTableIndex()
    {
        return view('manage.table.add');
    }

    public function addTable(Request $request)
    {
        // validate credentials
        $validatedData = $request->validate([
            'table_num' => ['required', 'unique:tables,table_number', 'digits_between:1,2'],
            'capacity' => ['required', 'digits_between:1,2'],
            ],
        );

        // insert data
        $form = Table::create([
            'table_number' => $request->table_num,
            'seats' => $request->capacity,
        ]);

        Session::flash('success','Table added successfully');
        return response()->json(['success'=>'Table added successfully']);
    }

    public function viewTable($id)
    {
        $table = Table::where('id', $id)->first();

        return view('manage.table.edit', [
            'table' => $table,
        ]);
    }

    public function editTable(Request $request, $id)
    {
        // validate credentials
        $table = Table::find($id);
        $validatedData = $request->validate([
            'table_num' => ['required', 'digits_between:1,2'],
            'capacity' => ['required', 'digits_between:1,2'],
            ],
        );
        // Rule::unique('tables,table_number')->where('table_number', '!=', $table->table_number)

        // update data
        $table->update([
            'table_number' => $request->table_num,
            'seats' => $request->capacity,
        ]);

        Session::flash('success','Table updated successfully');
        return response()->json(['success'=>'Table updated successfully']);
    }

    public function deleteTable($id)
    {
        $table = Table::find($id);
        $table->delete();
        Session::flash('success','Table deleted successfully');
        return redirect('/table');
    }
}
