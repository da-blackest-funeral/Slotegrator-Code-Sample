<?php

namespace App\Interfaces;

use App\DTO\CreateOrderDto;
use App\DTO\OrderFilteringDto;
use App\Enums\NotificationTypeEnum;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderServiceInterface
{
    public function setUser(User $user): static;

    public function getOrders(int $perPage): LengthAwarePaginator;

    public function filterOrders(OrderFilteringDto $dto, int $perPage);
}
