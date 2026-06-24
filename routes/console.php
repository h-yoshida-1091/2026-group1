<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Contact;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 毎日深夜に、ゴミ箱の中で「作成日時（または更新日時）が30日前」のデータを自動物理削除する
Schedule::call(function () {
    Contact::where('status', 'ゴミ箱')
        ->where('updated_at', '<=', now()->subDays(30))
        ->delete();
})->daily();