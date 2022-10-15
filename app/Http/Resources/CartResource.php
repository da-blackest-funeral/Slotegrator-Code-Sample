<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Product $this */
        return [
            'id' => $this->id,
            'count' => $this->pivot->count,
            'auction_id' => $this->pivot->auction_id,
            'name' => $this->name,
            'count_in_box' => $this->count_in_box,
            'boxes_in_pallet' => $this->count_boxes_in_pallet,
            'price' => $this->price,
            'weight' => $this->pallet_weight,
            'deleted' => (bool)$this->pivot->deleted_at,
            'country' => $this->country,
            'manufacturer' => $this->manufacturer
        ];
    }
}
