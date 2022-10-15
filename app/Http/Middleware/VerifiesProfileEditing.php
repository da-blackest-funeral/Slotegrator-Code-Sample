<?php

namespace App\Http\Middleware;

use App\Exceptions\EditException;
use App\Http\Requests\UpdateProfileRequest;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PharIo\Manifest\ElementCollectionException;

class VerifiesProfileEditing
{
    public function handle(Request $request, Closure $next)
    {
        /** @var UpdateProfileRequest $request */
        if (\Gate::allows('update-user', $request->user)) {
            return $next($request);
        }

         return new JsonResponse([
             'message' => __('profile.edit_not_allowed')
         ], 403);
    }
}
