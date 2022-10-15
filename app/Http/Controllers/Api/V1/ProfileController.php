<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class ProfileController extends Controller
{
    /**
     * @OA\Post(
     *     path="/profile/{user}",
     *     summary="Update User Email or Phone",
     *     tags={"Profile"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                 ),@OA\Property(
     *                     property="phone",
     *                     type="string",
     *                 ),
     *                 example={"email": "email@mail.com", "phone": "+7(918)-123-45-67"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),@OA\Response(
     *         response=403,
     *         description="User Have not permissions to do that",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * ),
     */
    public function edit(UpdateProfileRequest $request, User $user): JsonResponse
    {
        if ($request->email) {
            $user->email = $request->email;
        }

        if ($request->phone) {
            $user->phone = $request->phone;
        }

        $user->saveOrFail();

        return new JsonResponse([
            'message' => __('profile.updated')
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/profile/{user}",
     *     summary="Delete another user by admin",
     *     tags={"Cart"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * ),
     */
    public function delete(User $user)
    {
        $user->deleteOrFail();

        return new JsonResponse([
            'message' => __('profile.delete_success')
        ]);
    }
}
