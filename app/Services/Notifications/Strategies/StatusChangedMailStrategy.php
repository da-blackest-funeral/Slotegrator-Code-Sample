<?php

namespace App\Services\Notifications\Strategies;

use App\Enums\StatusEnum;
use App\Mail\OrderStatusChanged;
use App\Models\Order;
use App\Models\User;
use Panoscape\History\History;

class StatusChangedMailStrategy extends MailNotificationStrategy
{
    public function __construct(
        private readonly StatusEnum $old,
        private readonly StatusEnum $new,
    ) {}

    public function send(User $user, Order $order, array $data = []): void
    {
        \Mail::to($this->formUsers($user))
            ->send(new OrderStatusChanged(
                old: $this->old,
                new: $this->new,
                order: $order
            ));
    }
}
