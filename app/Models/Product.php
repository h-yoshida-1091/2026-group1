<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'image_id',
        'name',
        'description',
        'price',
        'stock',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class)->orderBy('created_at', 'desc'); // 新しいレビュー順
    }
}
