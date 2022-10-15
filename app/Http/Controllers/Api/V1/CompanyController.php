<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\CompanyResource;
use OpenApi\Annotations as OA;

class CompanyController extends Controller
{
    /**
     * @OA\Get(
     *     path="/company/info",
     *     summary="Get info about company",
     *     tags={"Account"},
     *     @OA\Response(
     *         response=200,
     *         description="Company Data",
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
     *                         property="contract",
     *                         type="string",
     *                     ),@OA\Property(
     *                         property="payment_terms",
     *                         type="string",
     *                     ),@OA\Property(
     *                         property="address",
     *                         type="string",
     *                     ),@OA\Property(
     *                         property="delivery_method",
     *                         type="string",
     *                     ),@OA\Property(
     *                         property="users",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(
     *                                 property="id",
     *                                 type="integer",
     *                             ),@OA\Property(
     *                                  property="first_name",
     *                                  type="string",
     *                             ),@OA\Property(
     *                                 property="last_name",
     *                                 type="string",
     *                             ),@OA\Property(
     *                                 property="email",
     *                                 type="string",
     *                             ),@OA\Property(
     *                                 property="phone",
     *                                 type="string",
     *                             ),
     *                         ),
     *                     ),@OA\Property(
     *                         property="addresses",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(
     *                                 property="id",
     *                                 type="integer",
     *                             ),@OA\Property(
     *                                 property="address",
     *                                 type="string",
     *                             ),
     *                         ),
     *                     ),@OA\Property(
     *                         property="responsible",
     *                         type="object",
     *                     ),@OA\Property(
     *                         property="manager",
     *                         type="object",
     *                     ),@OA\Property(
     *                         property="national_manager",
     *                         type="object",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * )
     */
    public function info(): CompanyResource
    {
        $companyData = \Auth::user()->company;

        return new CompanyResource($companyData);
    }
}
