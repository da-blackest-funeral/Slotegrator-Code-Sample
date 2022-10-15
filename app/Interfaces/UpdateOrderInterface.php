<?php

namespace App\Interfaces;

use App\DTO\UpdateOrderDto;
use App\Models\Order;
use App\Models\Product;

interface UpdateOrderInterface
{
    public function update(Order $order, UpdateOrderDto $dto): void;

    public function addProduct(Order $order, Product $product, int $count): void;

    public function removeProduct(Order $order, Product $product): void;
}
