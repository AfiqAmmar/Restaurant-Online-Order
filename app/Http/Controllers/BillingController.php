<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tables = Table::orderBy('table_number', 'ASC')->get();
        return view('manage.billing.index', [
            'tables' => $tables,
        ]);
    }

    public function invoice()
    {
        date_default_timezone_set("Asia/Bangkok");
        $today_date = date("d/m/Y");
        $current_time = date("h:ia");
        return view('manage.billing.invoice', [
            'date' => $today_date,
            'time' => $current_time,
        ]);
    }
}
