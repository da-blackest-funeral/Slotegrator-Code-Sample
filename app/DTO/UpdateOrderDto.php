<?php

namespace App\DTO;

use App\Http\Requests\UpdateOrderRequest;
use App\Models\User;

class UpdateOrderDto
{
    public function __construct(
        public ?int $status,
        public ?string $shipment_predict_date,
        public ?string $shipment_real_date,
        public ?string $order_number,
        public User $user
    ) {}

    public static function fromRequest(UpdateOrderRequest $request)
    {
        return new self(
            status: $request->status,
            shipment_predict_date: $request->shipment_predict_date,
            shipment_real_date: $request->shipment_real_date,
            order_number: $request->order_number,
            user: $request->user(),
        );
    }
}
