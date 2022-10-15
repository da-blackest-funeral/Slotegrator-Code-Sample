<?php

namespace App\Services\Filters;

use App\DTO\CatalogFilterDto;
use App\Enums\SortDirectionEnum;
use App\Enums\CatalogSortFieldsEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ProductFilter extends AbstractFilter
{
    private ?CatalogSortFieldsEnum $sortField;

    private ?SortDirectionEnum $sortDirection;

    public function __construct(Builder $query, CatalogFilterDto $dto)
    {
        $this->query = $query;

        $this->stringValues = [
            'name' => $dto->search,
            'id' => $dto->search,
        ];

        $this->relationValues = [
            'referral' => \Arr::wrap($dto->referralId),
            'category' => \Arr::wrap($dto->categoryId),
        ];

        $this->betweenValues = [
            'price' => [$dto->minPrice, $dto->maxPrice]
        ];

        $this->sortField = $dto->sortField;
        $this->sortDirection = $dto->sortDirection;
    }

    public function applyFilters(): Builder
    {
        $this->filterByStrings()
            ->filterByRelations()
            ->betweenFilter();

        return $this->query;
    }

    public function orderByRelations(array $joins)
    {
        foreach ($joins as $join) {
            // sortField equals table name in singular form
            if (Str::singular($join[0]) == $this->sortField->value) {
                $this->query->leftJoin($join[0], $join[1], $join[2], $join[3]);
            }
        }

        $this->query->orderBy(
            column: $this->sortField->getColumn(),
            direction: $this->sortDirection?->value ?? SortDirectionEnum::ASC->value
        );
    }
}
