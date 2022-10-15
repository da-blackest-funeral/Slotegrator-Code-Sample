<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogFilterRequest;
use App\Http\Resources\CatalogResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\Referral;
use App\Services\CatalogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class CatalogController extends Controller
{
    public function __construct(private readonly CatalogService $service)
    {}

    /**
     * @OA\Get(
     *     path="/catalog",
     *     summary="Get Catalog Products",
     *     tags={"Catalog"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="filter",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(
     *                             property="category",
     *                             type="integer",
     *                         ),@OA\Property(
     *                             property="refferal",
     *                             type="integer",
     *                         ),@OA\Property(
     *                             property="search",
     *                             type="string",
     *                         ),@OA\Property(
     *                             property="min_price",
     *                             type="integer",
     *                         ),@OA\Property(
     *                             property="max_price",
     *                             type="integer",
     *                         ),@OA\Property(
     *                             property="new",
     *                             type="boolean",
     *                         ),
     *                     ),
     *                 ),
     *                 @OA\Property(
     *                     property="sortDirection",
     *                     type="string",
     *                     example="asc or desc"
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
     *                         property="name",
     *                         type="string",
     *                     ),@OA\Property(
     *                         property="temperature",
     *                         type="string",
     *                     ),@OA\Property(
     *                         property="category",
     *                         type="object",
     *                     ),@OA\Property(
     *                         property="referral",
     *                         type="object",
     *                     ),@OA\Property(
     *                         property="manufacturer",
     *                         type="object",
     *                     ),@OA\Property(
     *                         property="country",
     *                         type="object",
     *                     ),@OA\Property(
     *                         property="count",
     *                         type="integer",
     *                         description="count of products that current user added to cart"
     *                     ),@OA\Property(
     *                         property="count_in_box",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="boxes_in_pallet",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="pallet_weight",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="expires_at",
     *                         type="string",
     *                         description="time when product will become expired"
     *                     ),@OA\Property(
     *                         property="discount_percentage",
     *                         type="integer",
     *                         description="if null then product has no discount"
     *                     ),
     *                 )
     *             )
     *         }
     *     )
     * )
     */
    public function index(CatalogFilterRequest $request): JsonResponse
    {
        $dto = $request->getFilter();
        $products = $this->service->getProducts($dto);

        return new JsonResponse(
            tap($products)->transform(fn(Product $product) => new CatalogResource($product))
        );
    }

    /**
     * @OA\Get(
     *     path="/catalog/filters",
     *     summary="Get filters for catalog",
     *     tags={"Catalog"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="types",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer"),
     *                             @OA\Property(property="name", type="string"),
     *                         ),
     *                     ),@OA\Property(
     *                         property="referrals",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer"),
     *                             @OA\Property(property="name", type="string"),
     *                         ),
     *                     ),@OA\Property(
     *                         property="min_price",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="max_price",
     *                         type="integer",
     *                     ),
     *                 )
     *             )
     *         }
     *     )
     * )
     */
    public function filters()
    {
        return new JsonResponse([
            'data' => [
                'categories' => Category::all(['id', 'name']),
                'referrals' => Referral::all(['id', 'name']),
                'min_price' => Product::min('price'),
                'max_price' => Product::max('price'),
            ]
        ]);
    }
}
