<?php

namespace App\Notifications;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractSigned extends Notification
{
    use Queueable;

    public function __construct(
        public Contract $contract
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'contract_signed',
            'title'       => 'عقد جديد',
            'message'     => "تم توقيع عقد: {$this->contract->contract_type} بقيمة " .
                             number_format($this->contract->contract_value, 2) . " ₪",
            'contract_id' => $this->contract->id,
            'url'         => route('contracts.show', $this->contract),
            'icon'        => 'bi-file-earmark-check',
            'color'       => 'success',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('📄 عقد جديد')
            ->greeting("مرحباً {$notifiable->name}")
            ->line("تم توقيع عقد جديد:")
            ->line("📋 النوع: {$this->contract->contract_type}")
            ->line("💰 القيمة: " . number_format($this->contract->contract_value, 2) . " ₪")
            ->action('عرض العقد', route('contracts.show', $this->contract))
            ->line('شكراً لتعاملك معنا.');
    }
}