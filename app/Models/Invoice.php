<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount_exl_tax',
        'total_amount',
    ];
    
    public function orders()
    {
        return $this->belongsTo(Order::class);
    }
}
