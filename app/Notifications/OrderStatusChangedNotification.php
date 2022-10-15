<?php

namespace App\Notifications;

use App\Enums\StatusEnum;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly StatusEnum $old,
        private readonly StatusEnum $new,
        private readonly Order $order,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase(mixed $notifiable): array
    {
        return [
            'message' => __('orders.status.changed', [
                'old' => $this->old->name,
                'new' => $this->new->name,
                'order' => $this->order,
            ])
        ];
    }
}
