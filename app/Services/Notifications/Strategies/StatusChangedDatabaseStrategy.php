<?php

namespace App\Services\Notifications\Strategies;

use App\Enums\StatusEnum;
use App\Interfaces\NotificationStrategy;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderStatusChangedNotification;

class StatusChangedDatabaseStrategy implements NotificationStrategy
{
    public function __construct(
        private readonly StatusEnum $old,
        private readonly StatusEnum $new,
    ) {}

    public function send(User $user, Order $order, array $data = []): void
    {
        $user->notify(new OrderStatusChangedNotification(
            old: $this->old,
            new: $this->new,
            order: $order
        ));
    }
}
