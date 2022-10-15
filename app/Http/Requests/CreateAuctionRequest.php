<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $auctionNumber
 * @property-read ?int $productId
 */
class CreateAuctionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'auctionNumber' => ['required'],
            'productId' => ['exists:products,id', 'nullable']
        ];
    }
}
