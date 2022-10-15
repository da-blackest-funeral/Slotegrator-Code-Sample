<?php

namespace App\Services\Notifications\Strategies;

use App\Exports\OrderExport;
use App\Mail\OrderCreated;
use App\Models\Order;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class OrderCreatedMailStrategy extends MailNotificationStrategy
{
    public function send(User $user, Order $order, array $data = []): void
    {
        $path = $this->createFile("xls");
        Excel::store(new OrderExport($order->products), $path);

        $users = $this->formUsers($user);

        \Mail::to($users)->send(new OrderCreated($path, $order));
    }
}
