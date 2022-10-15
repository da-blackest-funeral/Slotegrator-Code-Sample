<?php

namespace App\Services\Notifications\Strategies;

use App\Interfaces\NotificationStrategy;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderCreated;

class DatabaseNotificationStrategy implements NotificationStrategy
{
    public function send(User $user, Order $order, array $data = []): void
    {
        $user->notify(new OrderCreated($order));
    }
}
