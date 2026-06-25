<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    // ★ 追加：フォームから一括で保存（送られてきた値を割り当て）できるようにする許可設定
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'title',
        'comment',
    ];

    /**
     * ★ 追加：このレビューを投稿したユーザー（Userモデル）との紐づけ
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ★ 追加：このレビューが紐づいている商品（Productモデル）との紐づけ
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}