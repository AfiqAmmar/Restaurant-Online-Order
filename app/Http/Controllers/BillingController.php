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
}
