<?php

namespace App\Http\Middleware;

use App\Enums\StatusEnum;
use App\Exceptions\OrderStatusException;
use App\Models\Order;
use Closure;
use Illuminate\Http\Request;

class VerifiesOrderStatusBeforeUpdate
{
    /**
     * @throws OrderStatusException
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var Order $order */
        $order = $request->order;

        if ($order->status != StatusEnum::REGISTRATION && $order->status != StatusEnum::COORDINATION) {
            throw new OrderStatusException(__('order.cant_update_by_status'));
        }

        return $next($request);
    }
}
