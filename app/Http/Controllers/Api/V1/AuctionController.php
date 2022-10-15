<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAuctionRequest;
use App\Models\Auction;
use App\Models\Cart;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class AuctionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/auctions",
     *     summary="Retrieving all user's auctions",
     *     tags={"Auctions"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(
     *                                 property="id",
     *                                 type="integer",
     *                             ),@OA\Property(
     *                                 property="number",
     *                                 type="string",
     *                             ),@OA\Property(
     *                                 property="user_id",
     *                                 type="integer",
     *                             ),
     *                         ),
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * )
     */
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'data' => \Auth::user()->auctions
        ]);
    }

    /**
     * @OA\Post(
     *     path="/auctions",
     *     summary="Store auction and attach it to cart product if presented productId",
     *     tags={"Auctions"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="productId",
     *                     type="integer",
     *                 ),@OA\Property(
     *                     property="auctionNumber",
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
     *                         property="data",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(
     *                                 property="id",
     *                                 type="integer",
     *                             ),@OA\Property(
     *                                 property="number",
     *                                 type="string",
     *                             ),@OA\Property(
     *                                 property="user_id",
     *                                 type="integer",
     *                             ),
     *                         ),
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * ),
     * @throws \Throwable
     */
    public function store(CreateAuctionRequest $request): JsonResponse
    {
        $auction = Auction::firstOrCreate([
            'user_id' => \Auth::id(),
            'number' => $request->auctionNumber,
        ]);

        if (!is_null($request->productId)) {
            Cart::findByPivot(\Auth::id(), $request->productId)
                ->update([
                    'auction_id' => $auction->id
                ]);
        }

        return new JsonResponse([
            'data' => $auction
        ]);
    }
}
