<?php

namespace App\Interfaces;

use App\Enums\NotificationTypeEnum;
use App\Models\Order;
use App\Models\User;

interface OrderNotifier
{
    public function setUser(User $user): static;

    public function sendNotification(Order $order, NotificationStrategy $strategy, array $data = []);

    public function sendQueuedNotification(Order $order, NotificationStrategy $strategy, array $data = []);
}
