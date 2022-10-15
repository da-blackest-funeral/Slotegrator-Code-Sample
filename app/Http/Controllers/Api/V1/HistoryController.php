<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Panoscape\History\History;

class HistoryController extends Controller
{

    /**
     * @OA\Post(
     *     path="/histories/{history}",
     *     summary="Set history item as read",
     *     tags={"History"},
     *     @OA\Response(
     *         response=204,
     *         description="OK",
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
    public function markAsRead(History $history): JsonResponse
    {
        if ($history->user()->id == \Auth::id()) {
            $history->is_read = true;
            $history->save();
        } else {
            return new JsonResponse([
                'message' => __('history.forbidden')
            ], 403);
        }

        return new JsonResponse(status: 204);
    }
}
