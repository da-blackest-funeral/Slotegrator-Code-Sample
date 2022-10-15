<?php

namespace App\Http\Requests;

use App\DTO\CatalogFilterDto;
use App\Enums\SortDirectionEnum;
use App\Enums\CatalogSortFieldsEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * @property array|string $filter
 * @property-read string $sortBy
 * @property-read string $sortDirection
 * @property-read int $perPage
 */
class CatalogFilterRequest extends FormRequest
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
            'filter.category' => ['exists:categories,id'],
            'filter.referral' => ['exists:referrals,id'],
            'filter.search' => ['string'],
            'filter.min_price' => ['min:0',],
            'filter.max_price' => ['min:0',],
            'filter.new' => ['min:0', 'max:1'],
            'perPage' => ['min:1', 'max:1000'],

            'sortDirection' => [new Enum(SortDirectionEnum::class), 'nullable'],
            'sortBy' => [new Enum(CatalogSortFieldsEnum::class), 'nullable'],
        ];
    }

    public function getFilter(): CatalogFilterDto
    {
        return CatalogFilterDto::fromRequest($this);
    }
}
