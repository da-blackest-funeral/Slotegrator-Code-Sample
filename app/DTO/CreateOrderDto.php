<?php

namespace App\DTO;

use App\Enums\DeliveryMethodEnum;
use App\Http\Requests\CreateOrderRequest;
use App\Models\User;

class CreateOrderDto
{
    public function __construct(
        public User $user,
        public ?array $productIds,
        public ?int $addressId,
        public DeliveryMethodEnum $deliveryMethod,
        public ?string $number,
        public ?string $desired_shipment_date,
        public ?string $comment,
    ) {}

    public static function fromRequest(CreateOrderRequest $request): CreateOrderDto
    {
        return new self(
            user: $request->user(),
            productIds: $request->products,
            addressId: $request->addressId,
            deliveryMethod: $request->getDeliveryMethod(),
            number: $request->number,
            desired_shipment_date: $request->desiredShipmentDate,
            comment: $request->comment
        );
    }
}
