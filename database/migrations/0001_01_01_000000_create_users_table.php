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
        Schema::create('users', function (Blueprint $table) {
            //主キー、負数を使えない、自動採番
            $table->id();

            //ユーザー名
            $table->varchar('name', 100);

            //メールアドレス
            $table->varchar('email', 255);

            //パスワード
            $table->varchar('password', 255);

            //住所
            $table->varchar('address', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
