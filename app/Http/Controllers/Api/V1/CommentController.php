<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\PermissionEnum;
use App\Enums\RolesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;
use Spatie\Permission\Models\Role;

class CommentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/orders/{order}/comments",
     *     summary="Get all comments from order",
     *     tags={"Comments"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="commentable_id",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="comment",
     *                         type="string",
     *                     ),@OA\Property(
     *                         property="user_id",
     *                         type="integer",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * )
     */
    public function index(Order $order): JsonResponse
    {
        return new JsonResponse([
            'data' => CommentResource::collection($order->comments)
        ]);
    }

    /**
     * @OA\Post(
     *     path="/orders/{order}/comments",
     *     summary="Store comment",
     *     tags={"Comments"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="comment",
     *                     type="string",
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="commentable_id",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="comment",
     *                         type="string",
     *                     ),@OA\Property(
     *                         property="user_id",
     *                         type="integer",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),@OA\Response(
     *         response=403,
     *         description="Dont have permissions",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="error message"
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * ),
     */
    public function store(CommentRequest $request, Order $order): JsonResponse
    {
        if (! \Auth::user()->hasPermissionTo(PermissionEnum::COMMENT_IN_ORDERS->value)) {
            return new JsonResponse([
                'message' => __('comments.dont_have_permission')
            ], 403);
        }

        $comment = $order->comment($request->comment);

        return new JsonResponse([
            'data' => new CommentResource($comment),
        ]);
    }
}
