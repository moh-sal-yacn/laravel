<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Commands (Cron Jobs)
|--------------------------------------------------------------------------
*/

// ─── تذكيرات المواعيد والجلسات — كل يوم الساعة 9 صباحاً ───
Schedule::command('reminders:send')
    ->dailyAt('09:00')
    ->timezone('Asia/Gaza')
    ->description('إرسال تذكيرات المواعيد والجلسات');

// ─── (اختياري) تنظيف الإشعارات القديمة — كل شهر ───
Schedule::call(function () {
    \DB::table('notifications')
        ->where('read_at', '!=', null)
        ->where('created_at', '<', now()->subDays(30))
        ->delete();
})->monthlyOn(1, '03:00')
  ->description('حذف الإشعارات المقروءة القديمة');