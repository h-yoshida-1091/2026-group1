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
        Schema::create('order_items', function (Blueprint $table) {
            //注文明細ID
            $table->id();
            // 注文ID
            $table->string('order_id', 10);
            //商品ID
            $table->string('product_id', 10);
            // 個数
            $table->integer('quantity');
            // 作成日時、更新日時
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
