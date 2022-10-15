<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class CartSimpleResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Product $this */
        return [
            'id' => $this->id,
            'count' => $this->pivot->count,
            'auction_number' => $this->pivot->auction_number,
            'name' => $this->name,
            'price' => $this->price,
            'weight' => $this->pallet_weight,
            'boxes_in_pallet' => $this->count_boxes_in_pallet,
            'count_in_box' => $this->count_in_box,
        ];
    }
}
