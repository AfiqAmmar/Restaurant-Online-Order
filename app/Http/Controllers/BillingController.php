<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Table;
use App\Models\Tax;
use Illuminate\Http\Request;
use Ramsey\Uuid\Codec\OrderedTimeCodec;

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

    public function invoice($id)
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $today_date = date("d/m/Y");
        $current_time = date("h:ia");
        $table = Table::where('id', $id)->first();
        $taxes = Tax::all();
        $orders = $table->orders->where('payment_status', 0);
        $amount_exl_tax = 0;
        $amount_inlc_tax = 0;

        foreach($orders as $order)
        {
            // calculate amount exclude taxes
            foreach($order->menus as $ordering)
            {
                $amount_exl_tax = $amount_exl_tax + ($ordering->pivot->quantity * $ordering->price);
            }
        }

        $amount_inlc_tax = $amount_exl_tax;
        $tax_amount = array();

        // calculate amount include taxes and each tax amount
        foreach($taxes as $tax)
        {
            $tax_issue = ($tax->percentage/100) * $amount_exl_tax;
            $amount_inlc_tax = $amount_inlc_tax + $tax_issue;
            $tax_amount += [$tax->name => $tax_issue];
        }

        return view('manage.billing.invoice', [
            'date' => $today_date,
            'time' => $current_time,
            'lists' => $orders,
            'table' => $table,
            'amount_exl_tax' => $amount_exl_tax,
            'amount_incl_tax' => $amount_inlc_tax,
            'taxes' => $taxes,
            'tax_amount' => $tax_amount,
        ]);
    }

    public function submitPayment($id)
    {
        $table = Table::where('id', $id)->first();
        $taxes = Tax::all();
        $orders = $table->orders->where('payment_status', 0);

        foreach($orders as $order)
        {
            $amount_exl_tax = 0;
            $amount_inlc_tax = 0;

            // calculate amount exclude taxes
            foreach($order->menus as $ordering)
            {
                $amount_exl_tax = $amount_exl_tax + ($ordering->pivot->quantity * $ordering->price);
            }

            $amount_inlc_tax = $amount_exl_tax;
            $tax_amount = array();

            // calculate amount include taxes and each tax amount
            foreach($taxes as $tax)
            {
                $tax_issue = ($tax->percentage/100) * $amount_exl_tax;
                $amount_inlc_tax = $amount_inlc_tax + $tax_issue;
                $tax_amount += [$tax->name => $tax_issue];
            }
            
            $order->update([
                'payment_status' => 1,
            ]);
            
            $invoice_create = $order->invoices()->create([
                'amount_exl_tax' => $amount_exl_tax,
                'total_amount' => $amount_inlc_tax,
            ]);

        }

        return redirect('/billing');
    }
}
