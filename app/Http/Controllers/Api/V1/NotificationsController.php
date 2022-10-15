<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsController extends Controller
{
    public function read(DatabaseNotification $notification): JsonResponse
    {
        if ($notification->notifiable->id == \Auth::id()) {
            $notification->markAsRead();
        }

        return new JsonResponse(status: 204);
    }

    public function index()
    {
        return \Auth::user()
            ->notifications;
    }
}
