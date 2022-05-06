<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
    ];

    public function customers()
    {
        return $this->belongsTo(Customer::class);
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'cart__menus', 'cart_id', 'menu_id')->withPivot('quantity', 'remarks', 'sides');
    }
}
