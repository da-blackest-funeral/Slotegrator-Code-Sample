<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property string $temperature_conditions
 * @property int $manufacturer_id
 * @property int $country_id
 * @property float $price
 * @property int $recipe
 * @property int $count_in_box
 * @property int $count_boxes_in_pallet
 * @property float $pallet_weight
 * @property int $count
 * @property int $is_new
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCountBoxesInPallet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCountInBox($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereManufacturerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePalletWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereRecipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTemperatureConditions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\Country|null $country
 * @property-read \App\Models\Manufacturer|null $manufacturer
 * @method static \Database\Factories\ProductFactory factory(...$parameters)
 * @property int $referral_id
 * @property-read \App\Models\Referral|null $referral
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereReferralId($value)
 * @property string $expires_at
 * @property string $serial_number
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSerialNumber($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @property int|null $discount_percentage
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDiscountPercentage($value)
 * @property string|null $sku
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSku($value)
 * @property ?OrderItem $pivot
 */
class Product extends Model
{
    use HasFactory;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'cart')
            ->wherePivotNull('deleted_at')
            ->withPivot(['count', 'auction_id', 'deleted_at'])
            ->using(Cart::class);
    }
}
