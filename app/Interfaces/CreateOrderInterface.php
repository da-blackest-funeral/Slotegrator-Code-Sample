<?php

namespace App\Interfaces;

use App\DTO\CreateOrderDto;
use App\Models\Order;

interface CreateOrderInterface
{
    public function createOrder(CreateOrderDto $dto): Order;
}
