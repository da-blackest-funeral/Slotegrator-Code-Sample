<?php

namespace App\Models;

use App\Enums\DeliveryMethodEnum;
use App\Enums\OrderTypeEnum;
use App\Enums\StatusEnum;
use BeyondCode\Comments\Traits\HasComments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Panoscape\History\HasHistories;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property string $serial_number
 * @property int $user_id
 * @property string $expires_at
 * @property string $shipment
 * @property float $total_cost
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShipment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\OrderFactory factory(...$parameters)
 * @property string|null $shipment_predict_date
 * @property string|null $shipment_real_date
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShipmentPredictDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShipmentRealDate($value)
 * @property StatusEnum $status
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\BeyondCode\Comments\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Panoscape\History\History[] $histories
 * @property-read int|null $histories_count
 * @property int $address_id
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryMethod($value)
 * @property DeliveryMethodEnum $delivery_method
 * @property string|null $number
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereNumber($value)
 * @property-read \App\Models\User|null $user
 * @property string|null $desired_shipment_date
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDesiredShipmentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereType($value)
 * @property OrderTypeEnum $type
 */
class Order extends Model
{
    use HasFactory;
    use HasComments;
    use HasHistories;

    protected $casts = [
        'status' => StatusEnum::class,
        'delivery_method' => DeliveryMethodEnum::class,
        'type' => OrderTypeEnum::class,
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->using(OrderItem::class)
            ->withPivot(['count', 'auction_id', 'price']);
    }

    public function exportName(): string
    {
        return "order-$this->id.xls";
    }

    public function getModelLabel(): string
    {
        return $this->id;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setAddress(?int $addressId)
    {
        if ($this->delivery_method == DeliveryMethodEnum::SELF || is_null($addressId)) {
            $this->address_id = Address::default()->id;
        } else {
            $this->address_id = $addressId;
        }
    }
}
