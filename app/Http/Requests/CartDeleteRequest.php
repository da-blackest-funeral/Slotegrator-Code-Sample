<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\HasProductsValidation;
use Illuminate\Foundation\Http\FormRequest;

class CartDeleteRequest extends FormRequest
{
    use HasProductsValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->productRules();
    }
}
