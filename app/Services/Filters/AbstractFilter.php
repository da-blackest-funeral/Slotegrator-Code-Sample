<?php

namespace App\Services\Filters;

use InvalidArgumentException;
use Illuminate\Database\Eloquent\Builder;

abstract class AbstractFilter
{
    protected array $arrayValues = [];

    protected array $relationValues = [];

    protected array $stringValues = [];

    protected array $strictLikeComparisons = ['id'];

    protected array $betweenValues = [];

    protected array $dateValues = [];

    protected string $relationColumn = 'id';

    protected Builder $query;

    protected function filterByArrays(): self
    {
        foreach ($this->arrayValues as $key => $value) {
            if (empty($value)) {
                continue;
            }

            $this->query->whereIn($key, \Arr::wrap($value));
        }

        return $this;
    }

    protected function filterByDate(): self
    {
        foreach ($this->dateValues as $column => $dateValue) {
            if (empty($dateValue)) {
                continue;
            }

            $this->query->whereDate($column, $dateValue);
        }

        return $this;
    }

    protected function filterByRelations(): self
    {
        foreach ($this->relationValues as $relation => $value) {
            if (empty($value)) {
                continue;
            }

            $this->query->whereHas($relation, function (Builder $query) use ($value) {
                if (is_array($value)) {
                    $query->whereIn($this->relationColumn, $value);
                }

                if (is_string($value)) {
                    $query->where($this->relationColumn, 'like', "%$value%");
                }
            });
        }

        return $this;
    }

    protected function filterByStrings(): self
    {
        $this->query->where(function (Builder $query) {
            foreach ($this->stringValues as $column => $value) {
                if (empty($value)) {
                    continue;
                }

                $query->orWhere($column, 'like', $this->getLikeValue($column, $value));
            }
        });

        return $this;
    }

    protected function betweenFilter(): self
    {
        foreach ($this->betweenValues as $column => $betweenValue) {

            if (!empty($betweenValue[1]) && $betweenValue[0] > $betweenValue[1]) {
                throw new InvalidArgumentException('min value cannot be bigger than max value');
            }

            if (empty($betweenValue[0]) && empty($betweenValue[1])) {
                continue;
            }

            if (is_null($betweenValue[0])) {
                $this->lessThan($column, $betweenValue[1]);
                continue;
            }

            if (is_null($betweenValue[1])) {
                $this->query->where($column, '>=', $betweenValue[0]);
                continue;
            }

            $this->query->whereBetween($column, $betweenValue);
        }

        return $this;
    }

    private function lessThan(string $column, mixed $value): void
    {
        if (is_null($value)) {
            return;
        }

        $this->query->where($column, '<=', $value);
    }

    protected function getLikeValue(string $column, string $value)
    {
        if (in_array($column, $this->strictLikeComparisons)) {
            return $value;
        }

        return "%$value%";
    }

    abstract public function applyFilters(): Builder;
}
