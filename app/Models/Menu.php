<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'image_name',
        'image_path',
        'price',
        'sides',
        'availability',
        'available_quantity',
        'preparation_time'
    ];

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart__menus', 'menu_id', 'cart_id')->withPivot('quantity', 'remarks', 'sides');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'menu__orders', 'menu_id', 'order_id')->withPivot('quantity', 'menu_prepare', 'menu_serve', 'remarks', 'sides');
    }

    public function analyses()
    {
        return $this->hasOne(Analysis::class);
    }
}
