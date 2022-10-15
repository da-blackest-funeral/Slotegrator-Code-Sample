<?php

namespace App\Http\Middleware;

use App\Http\Requests\UpdateProfileRequest;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifiesDeletingUsers
{
    public function handle(Request $request, Closure $next)
    {
        /** @var UpdateProfileRequest $request */
        if (\Gate::allows('delete-user', $request->user)) {
            return $next($request);
        }

        return new JsonResponse([
            'message' => __('profile.delete_not_allowed')
        ], 403);
    }
}
