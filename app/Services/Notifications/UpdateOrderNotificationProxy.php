<?php

namespace App\Services\Notifications;

use App\DTO\UpdateOrderDto;
use App\Enums\NotificationTypeEnum;
use App\Interfaces\OrderNotifier;
use App\Interfaces\UpdateOrderInterface;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\Notifications\Strategies\OrderCreatedMailStrategy;
use App\Services\Notifications\Strategies\StatusChangedDatabaseStrategy;
use App\Services\Notifications\Strategies\StatusChangedMailStrategy;

class UpdateOrderNotificationProxy implements UpdateOrderInterface
{
    public function __construct(
        private readonly UpdateOrderInterface $service,
        private readonly OrderNotifier $notifier,
    ) {}

    public function update(Order $order, UpdateOrderDto $dto): void
    {
        $old = $order->status;

        $this->service->update($order, $dto);

        $new = $order->status;

        if ($old != $new) {
            $this->notifier->setUser($order->user)
                ->sendQueuedNotification($order, new StatusChangedMailStrategy($old, $new));

            $this->notifier->sendNotification($order, new StatusChangedDatabaseStrategy($old, $new));
        }
    }

    public function addProduct(Order $order, Product $product, int $count): void
    {
        $this->service->addProduct($order, $product, $count);
        $this->notifier->setUser($order->user)
            ->sendQueuedNotification($order, new OrderCreatedMailStrategy);
    }

    public function removeProduct(Order $order, Product $product): void
    {
        $this->service->removeProduct($order, $product);
        $this->notifier->setUser($order->user)
            ->sendQueuedNotification($order, new OrderCreatedMailStrategy);
    }
}
