<?php

namespace App\Services\Notifications;

use App\Interfaces\NotificationStrategy;
use App\Interfaces\OrderNotifier;
use App\Models\Order;
use App\Models\User;

class OrderNotificationService implements OrderNotifier
{
    private User $user;

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function sendNotification(Order $order, NotificationStrategy $strategy, array $data = [])
    {
        $strategy->send($this->user, $order, $data);
    }

    public function sendQueuedNotification(Order $order, NotificationStrategy $strategy, array $data = [])
    {
        dispatch(fn() => $this->sendNotification($order, $strategy, $data))
            ->afterResponse();
    }
}
