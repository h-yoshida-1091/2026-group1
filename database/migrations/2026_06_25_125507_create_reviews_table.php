<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            // 外部キー：usersテーブルのidと連携（ユーザーが削除されたらレビューも自動削除）
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // 外部キー：productsテーブルのidと連携（商品が削除されたらレビューも自動削除）
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            $table->string('title')->nullable(); // レビュータイトル（任意）
            $table->text('comment'); // レビュー本文（必須）
            
            // スコア：全体の桁数2、小数点以下1桁（0.0 ～ 5.0 を格納）
            $table->decimal('rating', 2, 1);
            
            $table->timestamps(); // 投稿日時 (created_at, updated_at)

            // 1人のユーザーが同じ商品に複数回投稿できないようにユニーク制約を設定
            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
