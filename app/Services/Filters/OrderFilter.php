<?php

namespace App\Services\Filters;

use App\DTO\OrderFilteringDto;
use Illuminate\Database\Eloquent\Builder;

class OrderFilter extends AbstractFilter
{
    public function __construct($query, OrderFilteringDto $dto)
    {
        $this->query = $query;

        $this->arrayValues = [
            'status' => $dto->status,
        ];

        $this->betweenValues = [
            'total_cost' => [$dto->min_price, $dto->max_price]
        ];

        $this->dateValues = [
            'created_at' => $dto->date,
        ];
    }

    public function applyFilters(): Builder
    {
        $this->filterByArrays()
            ->betweenFilter()
            ->filterByDate();

        return $this->query;
    }
}
