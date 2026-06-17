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
        Schema::create('products', function (Blueprint $table) {
            // 商品ID（主キー、自動採番）
            $table->id();

            // カテゴリーID
            $table->string('category_id', 10);

            // 画像ID
            $table->string('image_id', 10);

            // 商品名
            $table->string('name', 100);

            // 商品説明
            $table->string('description', 255);

            // 単価
            $table->integer('price');

            // 在庫数
            $table->integer('stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
