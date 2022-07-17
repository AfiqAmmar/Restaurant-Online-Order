<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tax;
use Illuminate\Support\Facades\Session;

class TaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('manage.tax.index');
    }

    public function getTaxes(Request $request)
    {
        try{
            $search_arr[] = $request->get('search');
        }
        catch (\Exception $e){
            $search_arr[] = '';
        }

        $columns = array(
            0 => 'tax_name',
            1 => 'percentage',
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

        $taxesCount = Tax::count();
        $totalFiltered = $taxesCount;
        $totalData = $totalFiltered;

        if($search_arr[0]['value'] == null){
            $taxes = Tax::orderBy('id', 'DESC')->skip($start)->take($rowPerPage)->get();
        }
        else{
            $taxes = Tax::where('name', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orWhere('percentage', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orderBy('id', 'DESC')->skip($start)->take($rowPerPage)->get();

            $totalFiltered = Tax::where('name', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orWhere('percentage', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orderBy('id', 'DESC')->skip($start)->take($rowPerPage)
                ->count();

            $totalData = $totalFiltered;
        }

        $data = [];

        if(!(empty($taxes))){
            foreach($taxes as $tax)
            {
                $editButton = '<div class="btn-group">
                                <button type="button" class="btn btn-primary"><a href="'. url('tax/' . $tax->id ) .'" class="text-white">Edit</a></button>
                              </div>';

                $data[] = array(
                    'tax_name' => $tax->name,
                    'percentage' => $tax->percentage,
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

    public function addTaxIndex()
    {
        return view('manage.tax.add');
    }

    public function addTax(Request $request)
    {
        // validate credentials
        $validatedData = $request->validate([
            'taxName' => ['required', 'string', 'max:255'],
            'percentage' => ['required', 'digits_between:1,2'],
            ],
        );

        // insert data
        $form = Tax::create([
            'name' => $request->taxName,
            'percentage' => $request->percentage,
        ]);

        Session::flash('success','Tax added successfully');
        return response()->json(['success'=>'Tax added successfully']);
    }

    public function viewTax($id)
    {
        $tax = Tax::where('id', $id)->first();

        return view('manage.tax.edit', [
            'tax' => $tax,
        ]);
    }

    public function editTax(Request $request, $id)
    {
        // validate credentials
        $validatedData = $request->validate([
            'taxName' => ['required', 'string', 'max:255'],
            'percentage' => ['required', 'digits_between:1,2'],
            ],
        );

        // update data
        $tax = Tax::find($id);
        $tax->update([
            'name' => $request->taxName,
            'percentage' => $request->percentage,
        ]);

        Session::flash('success','Tax updated successfully');
        return response()->json(['success'=>'Tax updated successfully']);
    }

    public function deleteTax($id)
    {
        $tax = Tax::find($id);
        $tax->delete();
        Session::flash('success','Tax deleted successfully');
        return redirect('/tax');
    }
}
