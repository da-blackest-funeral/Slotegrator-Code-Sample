<?php

namespace App\Http\Requests;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * @property-read ?string $shipment_predict_date
 * @property-read ?string $shipment_real_date
 * @property-read ?int $status
 * @property-read ?string $order_number
 */
class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipment_predict_date' => ['date_format:Y.m.d', 'nullable',],
            'shipment_real_date' => ['date_format:Y.m.d', 'nullable',],
            'status' => [new Enum(StatusEnum::class)],
        ];
    }
}
