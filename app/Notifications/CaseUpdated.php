<?php

namespace App\Notifications;

use App\Models\CourtCase;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CaseUpdated extends Notification
{
    use Queueable;

    public function __construct(
        public CourtCase $case,
        public string $changeDescription = 'تم تحديث بيانات القضية'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'      => 'case_updated',
            'title'     => 'تحديث قضية',
            'message'   => "{$this->changeDescription} — القضية: {$this->case->case_number}",
            'case_id'   => $this->case->id,
            'url'       => route('cases.show', $this->case),
            'icon'      => 'bi-pencil-square',
            'color'     => 'info',
        ];
    }
}