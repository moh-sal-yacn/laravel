<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminder extends Notification
{
    use Queueable;

    public function __construct(
        public Appointment $appointment
    ) {}

    /**
     * قنوات الإرسال
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];   // database + email
    }

    /**
     * إشعار قاعدة البيانات
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type'           => 'appointment_reminder',
            'title'          => 'تذكير بموعد قريب',
            'message'        => "لديك موعد يوم " . $this->appointment->appointment_date->translatedFormat('l، d F Y') .
                                " الساعة " . $this->appointment->appointment_date->format('h:i A'),
            'appointment_id' => $this->appointment->id,
            'date'           => $this->appointment->appointment_date->toISOString(),
            'url'            => route('appointments.show', $this->appointment),
            'icon'           => 'bi-calendar-check',
            'color'          => 'primary',
        ];
    }

    /**
     * إشعار البريد الإلكتروني
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🔔 تذكير: لديك موعد قريب')
            ->greeting("مرحباً {$notifiable->name}")
            ->line("لديك موعد مجدول:")
            ->line("📅 " . $this->appointment->appointment_date->translatedFormat('l، d F Y'))
            ->line("🕐 " . $this->appointment->appointment_date->format('h:i A'))
            ->action('عرض الموعد', route('appointments.show', $this->appointment))
            ->line('نتمنى لك يوماً موفقاً!');
    }
}