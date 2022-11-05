<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'table_id',
        'prepare_status',
        'serve_status',
        'estimate_time',
        'payment_status',
        'order_confirmed',
    ];

    public function customers()
    {
        return $this->belongsTo(Customer::class);
    }

    public function tables()
    {
        return $this->belongsTo(Table::class);
    }

    public function invoices()
    {
        return $this->hasOne(Invoice::class);
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu__orders', 'order_id', 'menu_id')->withPivot('quantity', 'menu_prepare', 'menu_serve', 'remarks', 'sides');
    }
}
