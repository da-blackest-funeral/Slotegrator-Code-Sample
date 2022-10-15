<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class CatalogResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Product $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'temperature' => $this->temperature_conditions,
            'category' => $this->category,
            'referral' => $this->referral,
            'manufacturer' => $this->manufacturer,
            'country' => $this->country,
            'price' => $this->price,
            'recipe' => $this->recipe,
            'count_in_box' => $this->count_in_box,
            'boxes_in_pallet' => $this->count_boxes_in_pallet,
            'count' => $this->users
                ->where('id', \Auth::id())
                ->first()
                ?->pivot->count,
            'pallet_weight' => $this->pallet_weight,
            'serial_number' => $this->serial_number,
            'expires_at' => $this->expires_at,
            'discount_percentage' => $this->discount_percentage
        ];
    }
}
