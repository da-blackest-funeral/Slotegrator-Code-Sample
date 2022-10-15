<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Panoscape\History\HasHistories;

/**
 * App\Models\OrderItem
 *
 * @property int $order_id
 * @property int $product_id
 * @property int $count
 * @property int|null $auction_id
 * @property float|null $price
 * @property int $id
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereAuctionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereProductId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Panoscape\History\History[] $histories
 * @property-read int|null $histories_count
 */
class OrderItem extends Pivot
{
    use HasHistories;

    protected $table = 'order_product';

    public $timestamps = false;

    public static function findByPivotValues(int $orderId, int $productId): static
    {
        return static::whereProductId($productId)
            ->where('order_id', $orderId)
            ->firstOrFail();
    }

    public function getModelLabel(): ?int
    {
        return $this->id;
    }
}
