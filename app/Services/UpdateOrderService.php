<?php

namespace App\Services;

use App\DTO\UpdateOrderDto;
use App\Enums\NotificationTypeEnum;
use App\Enums\StatusEnum;
use App\Interfaces\UpdateOrderInterface;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class UpdateOrderService implements UpdateOrderInterface
{



    public function addProduct(Order $order, Product $product, int $count): void
    {
        \DB::transaction(function () use ($count, $product, $order) {
            $item = OrderItem::updateOrCreate([
                'order_id' => $order->id,
                'product_id' => $product->id,
            ], [
                'count' => $count,
                'price' => $product->price,
            ]);

            $order->total_cost += $item->count * $item->price;
            $order->save();
        });
    }

    public function update(Order $order, UpdateOrderDto $dto): void
    {
        if (!is_null($dto->status) && $order->status->value != $dto->status) {
            $order->status = StatusEnum::from($dto->status);
        }

        $order->shipment_predict_date = $dto->shipment_predict_date ?? $order->shipment_predict_date;
        $order->shipment_real_date = $dto->shipment_real_date ?? $order->shipment_real_date;
        $order->number = $dto->order_number ?? $order->number;

        $order->saveOrFail();
    }

    public function removeProduct(Order $order, Product $product): void
    {
        $orderItem = OrderItem::findByPivotValues($order->id, $product->id);

        \DB::transaction(function () use ($orderItem, $order) {
            $order->total_cost -= $orderItem->count * $orderItem->price;
            $order->save();
            $orderItem->delete();
        });
    }
}
