<?php

namespace App\Http\Controllers\Api\V1;

use App\DTO\CreateOrderDto;
use App\DTO\OrderFilteringDto;
use App\DTO\UpdateOrderDto;
use App\Enums\NotificationTypeEnum;
use App\Enums\StatusEnum;
use App\Exports\OrderExport;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\OrderFilterRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Interfaces\CreateOrderInterface;
use App\Interfaces\OrderServiceInterface;
use App\Interfaces\UpdateOrderInterface;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderServiceInterface $service,
        private readonly CreateOrderInterface $createOrderService,
        private readonly UpdateOrderInterface $updateOrderService,
    ) {}

    /**
     * @OA\Get(
     *     path="/orders",
     *     summary="Get Paginated Orders",
     *     tags={"Orders"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="filter",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(
     *                             property="date",
     *                             type="string",
     *                         ),@OA\Property(
     *                             property="min_price",
     *                             type="integer",
     *                         ),@OA\Property(
     *                             property="max_price",
     *                             type="integer",
     *                         ),
     *                     ),
     *                 ),@OA\Property(
     *                     property="sortDirection",
     *                     type="string",
     *                 ),@OA\Property(
     *                     property="sortBy",
     *                     type="string",
     *                 ),
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
     *                         property="id",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="created_at",
     *                         type="date",
     *                         description="Datetime when order was created"
     *                     ),@OA\Property(
     *                         property="status",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="sum",
     *                         type="integer",
     *                         description="Total order price"
     *                     ),@OA\Property(
     *                         property="shipment_predict_date",
     *                         type="string",
     *                         description="Aproximate date when order will be delivered"
     *                     ),@OA\Property(
     *                         property="shipment_real_date",
     *                         type="string",
     *                         description="Date when order was delivered to company"
     *                     ),
     *                 )
     *             )
     *         }
     *     )
     * )
     */
    public function index(OrderFilterRequest $request)
    {
        $dto = OrderFilteringDto::fromRequest($request);
        $perPage = $request->perPage ?? 10;
        $orders = $this->service->filterOrders($dto, $perPage);

        return tap($orders)
            ->transform(fn(Order $order) => new OrderResource($order));
    }

    /**
     * @OA\Post(
     *     path="/orders",
     *     summary="Create order based on user's cart",
     *     tags={"Orders"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="products",
     *                     type="array",
     *                     description="can be null if need to add ALL products in order",
     *                     @OA\Items(
     *                         type="integer"
     *                     ),
     *                 ),@OA\Property(
     *                     property="addressId",
     *                     type="integer",
     *                     description="if null then will be taken default global address",
     *                 ),@OA\Property(
     *                     property="number",
     *                     type="string",
     *                     description="order number from stada",
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
     *                         property="user_id",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="total_cost",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="status",
     *                         type="integer",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * ),
     * @throws \Throwable
     */
    public function store(CreateOrderRequest $request): JsonResponse
    {
        $dto = CreateOrderDto::fromRequest($request);

        $order = \DB::transaction(fn() => $this->createOrderService->createOrder($dto));

        return new JsonResponse([
           'data' => $order->load('products')
        ]);
    }

    public function update(Order $order, UpdateOrderRequest $request): JsonResponse
    {
        $this->updateOrderService->update($order, UpdateOrderDto::fromRequest($request));

        return new JsonResponse([
            'message' => __('orders.updated')
        ]);
    }

    public function show(Order $order): Order
    {
        return $order->load('products');
    }

    public function destroy()
    {

    }

    /**
     * @OA\Get(
     *     path="/orders/{order}/export",
     *     summary="Get Excel File by Order's products",
     *     tags={"Orders"},
     *     @OA\Response(
     *         response=200,
     *         description="Binary file response to download",
     *     ),
     * ),
     */
    public function export(Order $order): BinaryFileResponse|JsonResponse
    {
        if ($order->user_id != \Auth::id()) {
            return new JsonResponse([
                'message' => __('export.cant_export')
            ], 403);
        }

        return Excel::download(
            new OrderExport($order->products),
            $order->exportName()
        );
    }
}
