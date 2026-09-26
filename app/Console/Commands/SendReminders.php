<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\CaseSession;
use App\Notifications\AppointmentReminder;
use App\Notifications\CaseSessionReminder;
use Illuminate\Console\Command;

class SendReminders extends Command
{
    protected $signature   = 'reminders:send';
    protected $description = 'إرسال تذكيرات بالمواعيد والجلسات القريبة';

    public function handle(): int
    {
        $this->info('🔔 بدء إرسال التذكيرات...');

        // ═══════════════ تذكير المواعيد (قبل 24 ساعة) ═══════════════
        $appointments = Appointment::with(['client.user', 'lawyer'])
            ->where('status', 'مجدول')
            ->whereBetween('appointment_date', [
                now()->addHours(23),
                now()->addHours(25),
            ])
            ->get();

        $appointmentsCount = 0;
        foreach ($appointments as $appointment) {
            // إشعار المحامي
            if ($appointment->lawyer) {
                $appointment->lawyer->notify(new AppointmentReminder($appointment));
                $appointmentsCount++;
            }
            // إشعار الموكل
            if ($appointment->client?->user) {
                $appointment->client->user->notify(new AppointmentReminder($appointment));
                $appointmentsCount++;
            }
        }

        $this->info("✅ تم إرسال {$appointmentsCount} تذكير موعد.");

        // ═══════════════ تذكير الجلسات (قبل 48 ساعة) ═══════════════
        $sessions = CaseSession::with(['case.client.user', 'user'])
            ->whereBetween('session_date', [
                now()->addHours(47),
                now()->addHours(49),
            ])
            ->get();

        $sessionsCount = 0;
        foreach ($sessions as $session) {
            $notifyUsers = collect();

            // المحامي المسؤول
            if ($session->user) {
                $notifyUsers->push($session->user);
            }
            // الموكل
            if ($session->case?->client?->user) {
                $notifyUsers->push($session->case->client->user);
            }
            // أطراف القضية
            if ($session->case) {
                foreach ($session->case->participants as $participant) {
                    $notifyUsers->push($participant);
                }
            }

            $notifyUsers->unique('id')->each(function ($user) use ($session, &$sessionsCount) {
                $user->notify(new CaseSessionReminder($session));
                $sessionsCount++;
            });
        }

        $this->info("✅ تم إرسال {$sessionsCount} تذكير جلسة.");
        $this->info('🎉 اكتمل إرسال التذكيرات.');

        return self::SUCCESS;
    }
}