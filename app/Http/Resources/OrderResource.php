<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Order $this */
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'status' => $this->status ?? 'no status',
            'sum' => $this->total_cost,
            'shipment_predict_date' => $this->shipment_predict_date,
            'shipment_real_date' => $this->shipment_real_date,
            'history' => $this->histories,
        ];
    }
}
