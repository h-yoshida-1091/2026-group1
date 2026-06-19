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
        Schema::create('orders', function (Blueprint $table) {
            // 注文ID（主キー、自動採番）
            $table->id();

            // ユーザーID
            $table->string('user_id',100);

            // 合計金額
            $table->integer('sumprice');

            // 注文日時
            $table->date('order_date');

            // 作成日時、更新日時
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
