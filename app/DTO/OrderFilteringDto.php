<?php

namespace App\DTO;

use App\Enums\SortDirectionEnum;
use App\Enums\StatusEnum;
use App\Http\Requests\OrderFilterRequest;
use App\Models\User;

class OrderFilteringDto
{
    public function __construct(
        public ?StatusEnum $status,
        public ?string $date,
        public ?int $min_price,
        public ?int $max_price,
        public ?string $sortBy,
        public ?SortDirectionEnum $sortDirection,
        public User $user
    ) {}

    public static function fromRequest(OrderFilterRequest $request): OrderFilteringDto
    {
        return new self(
            status: $request->status(),
            date: $request->filter['date'] ?? null,
            min_price: $request->filter['min_price'] ?? null,
            max_price: $request->filter['max_price'] ?? null,
            sortBy: $request->sortBy,
            sortDirection: $request->sortDirection(),
            user: $request->user()
        );
    }
}
