<?php

namespace App\Http\Requests;

use App\Enums\SortDirectionEnum;
use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * @property-read ?array $filter
 * @property-read ?string $sortBy
 * @property-read ?string $sortDirection
 */

// todo добавить $perPage
class OrderFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'filter.date' => ['date', 'nullable'],
            'filter.status' => [new Enum(StatusEnum::class), 'nullable'],
            'filter.min_price' => ['min:0', 'nullable'],
            'filter.max_price' => ['min:0', 'nullable'],
            'sortDirection' => [new Enum(SortDirectionEnum::class), 'nullable']
        ];
    }

    public function status(): ?StatusEnum
    {
        if (is_null($this->filter['status'] ?? null)) {
            return null;
        }

        return StatusEnum::tryFrom($this->filter['status']);
    }

    public function sortDirection(): ?SortDirectionEnum
    {
        return SortDirectionEnum::tryFrom($this->sortDirection);
    }
}
