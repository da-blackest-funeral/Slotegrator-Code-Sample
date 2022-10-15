<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartDeleteRequest;
use App\Http\Requests\CartStoreRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\CartSimpleResource;
use App\Models\Cart;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

use function Symfony\Component\String\s;

class CartController extends Controller
{
    public function __construct(private readonly CartService $service)
    {}

    /**
     * @OA\Get(
     *     path="/cart-fast",
     *     summary="Get Cart Products",
     *     tags={"Cart"},
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
     *                         property="count",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="name",
     *                         type="string",
     *                     ),@OA\Property(
     *                         property="price",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="weight",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="auction_id",
     *                         type="integer",
     *                     )
     *                     @OA\Property(
     *                         property="count_in_box",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="boxes_in_pallet",
     *                         type="integer",
     *                     ),
     *                 )
     *             )
     *         }
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return new JsonResponse(
            CartSimpleResource::collection(\Auth::user()->cart)
        );
    }

    /**
     * @OA\Get(
     *     path="/cart",
     *     summary="Get Cart Products",
     *     tags={"Cart"},
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
     *                         property="count",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="name",
     *                         type="string",
     *                     ),@OA\Property(
     *                         property="price",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="weight",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="auction_id",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="count_in_box",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="boxes_in_pallet",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="manufacturer",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string"),
     *                     ),@OA\Property(
     *                         property="country",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string"),
     *                     ),
     *                 )
     *             )
     *         }
     *     )
     * )
     */
    public function all(Request $request): JsonResponse
    {
        $user = \Auth::user();

        $cart = $user->cartWithTrashed()
            ->with([
                'country',
                'manufacturer',
            ]);

        if (!$request->perPage) {
            $collection = $cart->get();
        } else {
            $collection = $cart->paginate($request->perPage);
        }

        $collection = tap($collection)
            ->transform(fn(Product $product) => new CartResource($product));

        return new JsonResponse($collection);
    }

    /**
     * @OA\Post(
     *     path="/cart/{product}",
     *     summary="Store \ update product in cart",
     *     tags={"Cart"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="count",
     *                     type="integer",
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
     *                         property="message",
     *                         type="string",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * ),
     */
    public function store(CartStoreRequest $request, Product $product): JsonResponse
    {
        $this->service->setUser($request->user());

        \DB::transaction(fn() => $this->service->addToCart($product, $request->count, $request->auction_number));

        return new JsonResponse([
            'message' => __('cart.store_success')
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/cart",
     *     summary="Remove products from cart",
     *     tags={"Cart"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="products",
     *                     type="array",
     *                     description="can be null if need to delete ALL products from cart",
     *                     @OA\Items(
     *                         type="integer"
     *                     ),
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
     *     ),
     * ),
     */
    public function remove(CartDeleteRequest $request): JsonResponse
    {
        $this->service->setUser($request->user());

        $products = $this->service->getProducts($request->products);
        $this->service->markAsDeleted($products);

        return new JsonResponse([
           'message' => __('cart.remove_success')
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/cart/force",
     *     summary="Force delete products from cart (not marking as deleted)",
     *     tags={"Cart"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="products",
     *                     type="array",
     *                     description="can be null if need to delete ALL products from cart",
     *                     @OA\Items(
     *                         type="integer"
     *                     ),
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
     *     ),
     * ),
     */
    public function removeForce(CartDeleteRequest $request): JsonResponse
    {
        $this->service->setUser($request->user());

        $products = $this->service->getProducts($request->products);
        $this->service->forceDelete($products);

        return new JsonResponse([
            'message' => __('cart.remove_success')
        ]);
    }

    /**
     * @OA\Put(
     *     path="/cart/{product}/restore",
     *     summary="Restore product that marked as deleted",
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
     *     ),@OA\Response(
     *         response=400,
     *         description="Product are not deleted or not found",
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
    public function restore(Product $product): JsonResponse
    {
        $cartItem = $this->service->setUser(\Auth::user())
            ->getDeletedCartItem($product->id);

        if ($cartItem?->restoreAsPivot()) {
           return new JsonResponse([
               'message' => __('cart.restore.success')
           ]);
        }

        return new JsonResponse([
           'message' => __('cart.restore.fail')
        ], 400);
    }
}
