<?php

namespace App\Notifications;

use App\Models\CourtCase;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CaseCreated extends Notification
{
    use Queueable;

    public function __construct(
        public CourtCase $case
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'      => 'case_created',
            'title'     => 'قضية جديدة',
            'message'   => "تم فتح قضية جديدة برقم {$this->case->case_number}: {$this->case->case_title}",
            'case_id'   => $this->case->id,
            'case_number' => $this->case->case_number,
            'url'       => route('cases.show', $this->case),
            'icon'      => 'bi-folder-plus',
            'color'     => 'success',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('📁 قضية جديدة')
            ->greeting("مرحباً {$notifiable->name}")
            ->line("تم فتح قضية جديدة:")
            ->line("📋 رقم القضية: {$this->case->case_number}")
            ->line("📝 العنوان: {$this->case->case_title}")
            ->action('عرض القضية', route('cases.show', $this->case))
            ->line('شكراً لثقتك بنا.');
    }
}