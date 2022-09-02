<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Table;
use App\Models\Tax;
use Illuminate\Http\Request;
use Ramsey\Uuid\Codec\OrderedTimeCodec;
use Illuminate\Support\Facades\App;

class BillingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tables = Table::orderBy('table_number', 'ASC')->get();
        $table_arr = array();
        foreach($tables as $table)
        {
            $count = sizeof($table->orders);
            $table_arr += [$table->id => $count];
        }
        return view('manage.billing.index', [
            'tables' => $tables,
            'table_arr' => $table_arr,
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

    public function viewPDF($id)
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

        $data = array(
            'date' => $today_date,
            'time' => $current_time,
            'lists' => $orders,
            'table' => $table,
            'amount_exl_tax' => $amount_exl_tax,
            'amount_incl_tax' => $amount_inlc_tax,
            'taxes' => $taxes,
            'tax_amount' => $tax_amount,
        );

        $pdf = App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('manage.billing.pdf', $data)->setPaper(array(0,0,300,900), 'potrait')->setOptions(['defaultFont' => 'sans-serif']);
        // $pdf->loadView('manage.billing.pdf', $data)->setPaper("a8", 'potrait')->setOptions(['defaultFont' => 'sans-serif']); // need to set margin and no page break
        return $pdf->stream('Invoice Table ' . $table->id . '.pdf');
    }

    public function submitPayment($id)
    {
        $table = Table::where('id', $id)->first();
        $taxes = Tax::all();
        $orders = $table->orders->where('payment_status', 1);

        foreach($orders as $order)
        {
            if(sizeof($order->menus) == 0)
            {
                continue;
            }

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
