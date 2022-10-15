<?php

namespace App\Services;

use App\DTO\CreateOrderDto;
use App\Enums\StatusEnum;
use App\Exceptions\CartIsEmptyException;
use App\Interfaces\CreateOrderServiceInterface;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CreateOrderService implements CreateOrderServiceInterface
{
    private User $user;

    /**
     * @throws CartIsEmptyException
     */
    public function createOrder(CreateOrderDto $dto): Order
    {
        $this->user = $dto->user;

        $products = $this->user->getCart($dto->productIds);

        if ($products->isEmpty()) {
            throw new CartIsEmptyException(__('cart.empty'));
        }

        $totalSum = $this->calculatePrice($products);

        $order = $this->fillOrderProperties($totalSum, $dto);

        if ($dto->comment) {
            $order->comment($dto->comment);
        }

        $this->moveProductsToOrder($order, $products);

        return $order;
    }

    private function fillOrderProperties(int $totalSum, CreateOrderDto $dto): Order
    {
        $order = new Order();
        $order->user_id = $this->user->id;
        $order->total_cost = $totalSum;
        $order->status = StatusEnum::REGISTRATION;
        $order->delivery_method = $dto->deliveryMethod;
        $order->number = $dto->number;
        $order->desired_shipment_date = $dto->desired_shipment_date;
        $order->setAddress($dto->addressId);

        $order->saveOrFail();

        return $order;
    }

    private function moveProductsToOrder(Order $order, Collection $products)
    {
        $pivotArray = $products->map(function (Product $product) {
            return [
                'count' => $product->pivot->count,
                'auction_id' => $product->pivot->auction_id,
                'price' => $product->price,
            ];
        })->toArray();

        $order->products()->saveMany(
            models: $products,
            pivotAttributes: $pivotArray
        );

        $this->user->cart()->detach($products);
    }

    private function calculatePrice(iterable $products): float|int
    {
        $totalSum = 0;
        foreach ($products as $product) {
            $totalSum += $product->price * $product->pivot->count;
        }

        return $totalSum;
    }

}
