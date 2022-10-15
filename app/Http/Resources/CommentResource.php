<?php

namespace App\Http\Resources;

use BeyondCode\Comments\Comment;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var Comment $this */
        return [
            'id' => $this->id,
            'order_id' => $this->commentable_id,
            'comment' => $this->comment,
            'user_id' => $this->user_id,
        ];
    }
}
