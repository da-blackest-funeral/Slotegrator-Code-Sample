<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Panoscape\History\HasHistories;

/**
 * App\Models\Cart
 *
 * @property int $user_id
 * @property int $product_id
 * @property int $count
 * @property string|null $auction_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereAuctionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUserId($value)
 * @mixin \Eloquent
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Cart onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|Cart withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Cart withoutTrashed()
 * @property int|null $auction_id
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereAuctionId($value)
 */
class Cart extends Pivot
{
    use SoftDeletes;

    public function restoreAsPivot(): ?bool
    {
        return static::findByPivot($this->user_id, $this->product_id)
            ->onlyTrashed()
            ->restore();
    }

    public static function findByPivot(int $userId, int $productId)
    {
        return self::whereUserId($userId)
            ->whereProductId($productId);
    }

    public function getModelLabel(): string
    {
        return 'cart';
    }
}
