<?php

namespace App\Services;

use App\DTO\CreateOrderDto;
use App\DTO\OrderFilteringDto;
use App\Enums\OrderTypeEnum;
use App\Exceptions\CartIsEmptyException;
use App\Interfaces\CreateOrderInterface;
use App\Interfaces\OrderServiceInterface;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SeparateOrdersDecorator implements CreateOrderInterface
{
    private Collection $productsWithAuctions;

    private Collection $productsWithoutAuctions;

    private CreateOrderDto $dto;

    public function __construct(private readonly CreateOrderInterface $service)
    {
        $this->productsWithoutAuctions = new Collection;
        $this->productsWithAuctions = new Collection;
    }

    /**
     * @throws \Exception
     */
    public function createOrder(CreateOrderDto $dto): Order
    {
        $this->dto = $dto;
        $products = $dto->user->getCart($dto->productIds);
        $this->separateProducts($products);
        $order = new Order;

        if ($this->productsWithAuctions->isNotEmpty()) {
            $order = $this->createOrderWithAuctions();
        }

        if ($this->productsWithoutAuctions->isNotEmpty()) {
            $order = $this->createOrderWithoutAuctions();
        }

        if (!$order->exists) {
            throw new CartIsEmptyException(__('cart.empty'));
        }

        return $order;
    }

    private function createOrderWithAuctions(): Order
    {
        $this->dto->productIds = $this->productsWithAuctions->toArray();
        $order = $this->service->createOrder($this->dto);

        $order->type = OrderTypeEnum::WITH_AUCTIONS;
        $order->save();

        return $order;
    }

    private function createOrderWithoutAuctions(): Order
    {
        $this->dto->productIds = $this->productsWithoutAuctions->toArray();
        $order = $this->service->createOrder($this->dto);

        $order->type = OrderTypeEnum::WITHOUT_AUCTIONS;
        $order->save();

        return $order;
    }

    private function separateProducts(Collection $products)
    {
        $products->each(function (Product $product) {
            if ($product->pivot->auction_id) {
                $this->productsWithAuctions->push($product->id);
            } else {
                $this->productsWithoutAuctions->push($product->id);
            }
        });
    }
}
