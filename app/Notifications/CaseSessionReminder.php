<?php

namespace App\Notifications;

use App\Models\CaseSession;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CaseSessionReminder extends Notification
{
    use Queueable;

    public function __construct(
        public CaseSession $session
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'      => 'session_reminder',
            'title'     => 'جلسة قضائية قريبة',
            'message'   => "لديك جلسة يوم " . $this->session->session_date->translatedFormat('l، d F Y') .
                           " الساعة " . $this->session->session_date->format('h:i A') .
                           " — القضية: " . ($this->session->case?->case_number ?? '—'),
            'session_id' => $this->session->id,
            'case_id'   => $this->session->cases_id,
            'date'      => $this->session->session_date->toISOString(),
            'url'       => $this->session->case ? route('cases.show', $this->session->case) : '#',
            'icon'      => 'bi-calendar-event',
            'color'     => 'warning',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚖️ تذكير: جلسة قضائية قريبة')
            ->greeting("مرحباً {$notifiable->name}")
            ->line("لديك جلسة قضائية:")
            ->line("📅 " . $this->session->session_date->translatedFormat('l، d F Y'))
            ->line("🕐 " . $this->session->session_date->format('h:i A'))
            ->line("📋 القضية: " . ($this->session->case?->case_number ?? '—'))
            ->action('عرض القضية', $this->session->case ? route('cases.show', $this->session->case) : '#')
            ->line('نتمنى لك التوفيق!');
    }
}