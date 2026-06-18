<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart_item extends Model
{
    protected $fillable = [ 
        'user_id',
        'product_id',
        'quantity',
    ];
}
