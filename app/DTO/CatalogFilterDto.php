<?php

namespace App\DTO;

use App\Enums\SortDirectionEnum;
use App\Enums\CatalogSortFieldsEnum;
use App\Http\Requests\CatalogFilterRequest;

class CatalogFilterDto
{
    public function __construct(
        public ?int $categoryId,
        public ?int $referralId,
        public ?string $search,
        public ?int $minPrice,
        public ?int $maxPrice,
        public ?int $perPage,
        public ?bool $new,
        public ?SortDirectionEnum $sortDirection,
        public ?CatalogSortFieldsEnum $sortField,
    ) {}

    public static function fromRequest(CatalogFilterRequest $request): CatalogFilterDto
    {
       if (is_string($request->filter)) {
           $request->filter = json_decode($request->filter, true);
       }

        return new self(
            categoryId: $request->filter['category'] ?? null,
            referralId: $request->filter['referral'] ?? null,
            search: $request->filter['search'] ?? null,
            minPrice: $request->filter['min_price'] ?? null,
            maxPrice: $request->filter['max_price'] ?? null,
            perPage: $request->perPage ?? 10,
            new: $request->filter['new'] ?? null,
            sortDirection: SortDirectionEnum::tryFrom($request->sortDirection),
            sortField: CatalogSortFieldsEnum::tryFrom($request->sortBy),
        );
    }
}
