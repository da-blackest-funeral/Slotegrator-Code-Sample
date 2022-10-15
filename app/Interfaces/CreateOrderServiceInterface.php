<?php

namespace App\Interfaces;

use App\DTO\CreateOrderDto;
use App\Models\Order;

interface CreateOrderServiceInterface
{
    public function createOrder(CreateOrderDto $dto): Order;
}
