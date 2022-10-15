<?php

namespace App\Services;

use App\DTO\CreateOrderDto;
use App\DTO\OrderFilteringDto;
use App\Enums\StatusEnum;
use App\Exceptions\CartIsEmptyException;
use App\Interfaces\NotificationStrategy;
use App\Interfaces\OrderServiceInterface;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\Filters\OrderFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderService implements OrderServiceInterface
{
    private User $user;

    private NotificationStrategy $strategy;

    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function filterOrders(OrderFilteringDto $dto, int $perPage): LengthAwarePaginator
    {
        $query = Order::where('user_id', $dto->user->id)
            ->with(['histories']);

        (new OrderFilter($query, $dto))
            ->applyFilters();

        if (!is_null($dto->sortBy)) {
            $query->orderBy($dto->sortBy, $dto->sortDirection?->value ?? 'asc');
        }

        return $query->paginate($perPage);
    }

    public function getOrders(int $perPage): LengthAwarePaginator
    {
        return Order::paginate($perPage);
    }
}
