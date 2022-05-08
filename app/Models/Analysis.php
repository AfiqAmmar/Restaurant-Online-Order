<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'orders',
    ];

    public function menus()
    {
        return $this->belongsTo(Menu::class);
    }
}
