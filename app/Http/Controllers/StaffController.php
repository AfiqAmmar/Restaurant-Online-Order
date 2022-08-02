<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('manage.staff.index');
    }

    public function getStaffs(Request $request)
    {
        try{
            $search_arr[] = $request->get('search');
        }
        catch (\Exception $e){
            $search_arr[] = '';
        }

        $columns = array(
            0 => 'fname',
            1 => 'lname',
            2 => 'email',
            3 => 'phone_number',
            4 => 'role',
            5 => 'start_date',
            6 => 'action',
        );

        $draw = $request->get('draw');
        $start = $request->get('start');
        $rowPerPage = $request->get('length');
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');

        $columnIndex = $columnIndex_arr[0]['column'];
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $columnIndex_arr[0]['dir'];

        $staffsCount = User::count();
        $totalFiltered = $staffsCount;
        $totalData = $totalFiltered;

        if($search_arr[0]['value'] == null){
            $staffs = User::orderBy('id', 'DESC')->skip($start)->take($rowPerPage)->get();
        }
        else{
            $staffs = User::where('fname', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orWhere('lname', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orWhere('email', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orWhere('phone_number', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orWhere('role', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orWhere('created_at', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orderBy('id', 'DESC')->skip($start)->take($rowPerPage)->get();

            $totalFiltered = User::where('fname', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orWhere('lname', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orWhere('email', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orWhere('phone_number', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orWhere('role', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orWhere('created_at', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orderBy('id', 'DESC')->skip($start)->take($rowPerPage)->get()
                ->count();

            $totalData = $totalFiltered;
        }

        $data = [];

        if(!(empty($staffs))){
            foreach($staffs as $staff)
            {
                $editButton = '<div class="btn-group">
                                <button type="button" class="btn btn-primary"><a href="'. url('staff/' . $staff->id ) .'" class="text-white">Edit</a></button>
                              </div>';
                
                $convertedDate = date('d/m/Y', strtotime($staff->start_date));

                $data[] = array(
                    'fname' => $staff->fname,
                    'lname' => $staff->lname,
                    'email' => $staff->email,
                    'phone_number' => $staff->phone_number,
                    'role' => $staff->role,
                    'start_date' => $convertedDate,
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
