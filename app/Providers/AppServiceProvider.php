<?php

namespace App\Providers;

use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\OrderItemController;
use App\Interfaces\CreateOrderServiceInterface;
use App\Interfaces\OrderServiceInterface;
use App\Interfaces\UpdateOrderInterface;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\CreateOrderService;
use App\Services\Notifications\OrderNotificationService;
use App\Services\Notifications\OrderServiceNotificationDecorator;
use App\Services\Notifications\UpdateOrderNotificationProxy;
use App\Services\OrderService;
use App\Services\SeparateOrdersDecorator;
use App\Services\UpdateOrderService;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(OrderServiceInterface::class, OrderService::class);

        $this->app->when([OrderController::class, OrderItemController::class])
            ->needs(CreateOrderServiceInterface::class)
            ->give(function () {
                return new SeparateOrdersDecorator(
                    new OrderServiceNotificationDecorator(
                        new CreateOrderService,
                        new OrderNotificationService,
                    ),
                );
            });

        $this->app->bind(UpdateOrderInterface::class, function () {
            return new UpdateOrderNotificationProxy(
                new UpdateOrderService,
                new OrderNotificationService,
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'order' => Order::class,
            'user' => User::class,
            'cart' => Cart::class,
            'order_item' => OrderItem::class,
        ]);
    }
}
