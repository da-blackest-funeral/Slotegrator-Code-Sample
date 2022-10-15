<?php

namespace App\Services\Notifications;

use App\DTO\CreateOrderDto;
use App\DTO\OrderFilteringDto;
use App\Enums\NotificationTypeEnum;
use App\Interfaces\CreateOrderServiceInterface;
use App\Interfaces\OrderNotifier;
use App\Interfaces\OrderServiceInterface;
use App\Models\Order;
use App\Models\User;
use App\Services\Notifications\Strategies\DatabaseNotificationStrategy;
use App\Services\Notifications\Strategies\OrderCreatedMailStrategy;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderServiceNotificationDecorator implements CreateOrderServiceInterface
{
    public function __construct(
        private readonly CreateOrderServiceInterface $service,
        private readonly OrderNotifier $notifier,
    ) {}

    public function setUser(User $user): static
    {
        $this->notifier->setUser($user);

        return $this;
    }

    public function createOrder(CreateOrderDto $dto): Order
    {
        $this->notifier->setUser($dto->user);
        $order = $this->service->createOrder($dto);

        $this->notifier->sendNotification($order, new DatabaseNotificationStrategy);
        $this->notifier->sendQueuedNotification($order, new OrderCreatedMailStrategy);

        return $order;
    }
}
