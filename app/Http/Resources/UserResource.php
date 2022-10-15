<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var User $this */

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'company' => $this->company->only(['id', 'name']),
            'role' => $this->roles->first()->name,
            'auctions' => $this->auctions,
            'phone' => $this->phone,
        ];
    }
}
