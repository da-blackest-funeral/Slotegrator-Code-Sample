<?php

namespace App\Http\Middleware;

use App\Exceptions\UserIsNotOwnerException;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifiesOrderOwner
{
    /**
     * @throws UserIsNotOwnerException
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->order->user_id != \Auth::id()) {
            throw new UserIsNotOwnerException(__('order.cant_update'));
        }

        return $next($request);
    }
}
