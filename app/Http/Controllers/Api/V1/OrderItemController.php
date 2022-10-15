<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Middleware\VerifiesOrderOwner;
use App\Http\Middleware\VerifiesOrderStatusBeforeUpdate;
use App\Http\Requests\StoreOrderItemRequest;
use App\Interfaces\OrderServiceInterface;
use App\Interfaces\UpdateOrderInterface;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class OrderItemController extends Controller
{
    public function __construct(private readonly UpdateOrderInterface $service)
    {
        $this->middleware([VerifiesOrderOwner::class, VerifiesOrderStatusBeforeUpdate::class]);
    }

    /**
     * @OA\Post(
     *     path="/orders/{order}/{product}",
     *     summary="Store \ Update product in order",
     *     tags={"Orders"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="count",
     *                     type="integer",
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
     *                         property="message",
     *                         type="string",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),@OA\Response(
     *         response=403,
     *         description="If order's user_id != id of authenticated user",
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
     * @throws \Throwable
     */
    public function store(Order $order, Product $product, StoreOrderItemRequest $request): JsonResponse
    {
        $this->service->addProduct($order, $product, $request->count);

        return new JsonResponse([
           'message' => __('order.updated')
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/orders/{order}/{product}",
     *     summary="Delete product from order",
     *     tags={"Orders"},
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
     *         description="If order's user_id != id of authenticated user",
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
    public function delete(Order $order, Product $product)
    {
        $this->service->removeProduct($order, $product);

        return new JsonResponse([
            'message' => __('order.product_deleted'),
        ]);
    }
}
