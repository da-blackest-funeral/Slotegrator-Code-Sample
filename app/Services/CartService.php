<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CartService
{
    private User $user;

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function addToCart(Product $product, int $count, string $auction_id = null): void
    {
        $cart = $this->user->cartWithTrashed();
        $deleted_at = null;

        if ($count == 0) {
            $deleted_at = now();
        }

        $cart->syncWithPivotValues(
            ids: $product,
            values: compact('count', 'auction_id', 'deleted_at'),
            detaching: false
        );
    }

    public function getProducts(?array $ids): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->user->cartWithTrashed()->when(!empty($ids), function (Builder $query) use ($ids) {
            $query->whereIn('products.id', $ids);
        })->get();
    }

    public function markAsDeleted(Collection|Product $products): void
    {
        $this->user->cart()
            ->updateExistingPivot($products, ['deleted_at' => now()]);
    }

    public function forceDelete(Collection $products): void
    {
        $this->user->cartWithTrashed()
            ->wherePivotIn('product_id', $products->pluck('id')->toArray())
            ->detach();
    }

    public function getDeletedCartItem(int $productId): ?Cart
    {
        return Cart::whereUserId($this->user->id)
            ->where('product_id', $productId)
            ->onlyTrashed()
            ->first();
    }
}
