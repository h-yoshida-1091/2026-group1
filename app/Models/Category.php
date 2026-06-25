<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // 🟢 追記：リレーション機能を使うための宣言

class Category extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * 🟢 追記：カテゴリーに属する商品（複数）を取得するリレーション
     * * 「1つのカテゴリー」に対して「商品はたくさん（HasMany）」存在するという関係性です。
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
