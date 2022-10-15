<?php

namespace App\Http\Requests\Traits;

/**
 * @property-read array|null $products
 */
trait HasProductsValidation
{
    protected function productRules(): array
    {
        return [
            'products.*' => ['exists:products,id', 'nullable'],
            'products' => ['array', 'nullable']
        ];
    }
}
