<?php

namespace App\Interfaces;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface NotificationStrategy
{
    public function send(User $user, Order $order, array $data = []): void;
}
