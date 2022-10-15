<?php

namespace App\Http\Requests;

use App\Enums\DeliveryMethodEnum;
use App\Http\Requests\Traits\HasProductsValidation;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read ?int $addressId
 * @property-read ?string $deliveryMethod
 * @property-read ?string $number
 * @property-read ?string $desiredShipmentDate
 * @property-read ?string $comment
 */
class CreateOrderRequest extends FormRequest
{
    use HasProductsValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge(
            [
                'addressId' => ['exists:addresses,id', 'nullable'],
                'desiredShipmentDate' => ['nullable', 'date_format:Y.m.d'],
            ],
            $this->productRules()
        );
    }

    public function getDeliveryMethod(): DeliveryMethodEnum
    {
        if (is_null($this->deliveryMethod)) {
            return DeliveryMethodEnum::SELF;
        }

        return DeliveryMethodEnum::from($this->deliveryMethod);
    }
}
