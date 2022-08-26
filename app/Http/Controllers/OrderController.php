<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexQueue()
    {
        return view('manage.order.queue');
    }

    public function indexHistory()
    {
        return view('manage.order.history');
    }

    public function getOrders(Request $request)
    {
        try {
            $search_arr[] = $request->get('search');
        } catch (\Exception $e) {
            $search_arr[] = '';
        }

        $columns = array(
            0 => 'order_number',
            1 => 'order_time',
            2 => 'order_date',
            3 => 'action'
        );

        $draw = $request->get('draw');
        $start = $request->get('start');
        $rowPerPage = $request->get('length');
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');

        $columnIndex = $columnIndex_arr[0]['column'];
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $columnIndex_arr[0]['dir'];

        $ordersCount = Order::count();
        $totalFiltered = $ordersCount;
        $totalData = $totalFiltered;

        if ($search_arr[0]['value'] == null) {
            $orders = Order::orderBy('id', 'DESC')->skip($start)
                ->take($rowPerPage)->get();
        } else {
            $orders = Order::where('id', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orWhere('created_at', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orderBy('id', 'DESC')->skip($start)
                ->take($rowPerPage)->get();

            $totalFiltered = Order::where('id', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orWhere('created_at', 'LIKE', "%{$search_arr[0]['value']}%")
                ->orderBy('id', 'DESC')->skip($start)
                ->take($rowPerPage)->count();

            $totalData = $totalFiltered;
        }

        $data = [];

        if (!(empty($orders))) {
            foreach ($orders as $order) {
                $editButton = '<div class="btn-group">
                                <button type="button" class="btn btn-primary">
                                    <a href="' . url('order/' . $order->id) . '" class="text-white">View More</a>
                                </button>
                              </div>';

                // $table_id = $order->table_id;
                // $table_number = Table::where('id', $table_id)->first()->table_number;

                $data[] = array(
                    'order_number' => $order->id,
                    'order_time' => $order->created_at->format('g:i a'),
                    'order_date' => $order->created_at->format('d/m/Y'),
                    'action' => $editButton
                );

                $keys = array_column($data, $columnName);
                if ($columnSortOrder == 'asc') {
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

    public function viewOrder($id)
    {
        $order = Order::find($id);
        $table = Table::where('id', $order->table_id)->first();

        return view('manage.order.view', [
            'order' => $order,
            'table' => $table
        ]);
    }

    public function getOrder(Request $request)
    {
        $columns = array(
            0 => 'name',
            1 => 'quantity',
            2 => 'remarks',
            3 => 'sides'
        );

        $draw = $request->get('draw');
        $start = $request->get('start');
        $rowPerPage = $request->get('length');
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');

        $columnIndex = $columnIndex_arr[0]['column'];
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $columnIndex_arr[0]['dir'];

        $order_id = $request->get('id');
        $order = Order::find($order_id);
        $menusCount = $order->menus()->count();
        $totalFiltered = $menusCount;
        $totalData = $totalFiltered;

        $menus = $order->menus()->skip($start)
            ->take($rowPerPage)->get();

        $data = [];

        if (!(empty($menus))) {
            foreach ($menus as $menu) {
                $sides = $menu->pivot->sides;

                if ($sides != 'N/A') {
                    $sidesArray = explode(', ', $menu->pivot->sides);
                    $sides = (count($sidesArray) == 1) ? $sidesArray : $this->displaySidesAsList($sidesArray);
                }

                $data[] = array(
                    'name' => $menu->name,
                    'quantity' => $menu->pivot->quantity,
                    'remarks' => $menu->pivot->remarks,
                    'sides' => $sides
                );

                $keys = array_column($data, $columnName);
                if ($columnSortOrder == 'asc') {
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

    public function displaySidesAsList($sides)
    {
        $output = '<ul>';
        foreach ($sides as $side) {
            $output .= '<li>' . $side . '</li>';
        }
        return $output . '</ul>';
    }
}
