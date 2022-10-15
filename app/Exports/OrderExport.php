<?php

namespace App\Exports;

use App\Http\Resources\CartResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrderExport implements FromCollection, ShouldAutoSize
{
    public function __construct(
        private readonly Collection $products)
    {}

    public function collection(): Collection
    {
        $productResource = CartResource::collection($this->products->all())
            ->toArray(request());

        $firstProduct = $productResource[0];

        $columns = array_keys($firstProduct);

        return collect([$columns])
            ->merge($productResource);
    }
}
