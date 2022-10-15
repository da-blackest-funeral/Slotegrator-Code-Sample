<?php

namespace App\Services;

use App\DTO\CatalogFilterDto;
use App\Models\Product;
use App\Services\Filters\ProductFilter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CatalogService
{
    private array $joins = [
        ['categories', 'categories.id', '=', 'products.category_id'],
        ['referrals', 'products.referral_id', '=', 'referrals.id'],
        ['countries', 'products.country_id', '=', 'countries.id'],
        ['manufacturers', 'products.manufacturer_id', '=', 'manufacturers.id'],
    ];

    public function getProducts(CatalogFilterDto $dto): LengthAwarePaginator
    {
        $query = Product::with([
            'category',
            'referral',
            'country',
            'manufacturer',
            'users',
        ]);

        $filter = new ProductFilter($query, $dto);
        $filter->applyFilters();

        $query->select('products.*');

        if (!is_null($dto->new)) {
            $query->where('is_new', $dto->new);
        }

        if (!is_null($dto->sortField)) {
            $filter->orderByRelations($this->joins);
        }

        return $query->paginate($dto->perPage);
    }
}
