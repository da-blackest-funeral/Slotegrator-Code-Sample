<?php

namespace App\Models;

use App\Enums\DeliveryMethodEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Company
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Company newModelQuery()
 * @method static Builder|Company newQuery()
 * @method static Builder|Company query()
 * @method static Builder|Company whereCreatedAt($value)
 * @method static Builder|Company whereId($value)
 * @method static Builder|Company whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $name
 * @method static \Database\Factories\CompanyFactory factory(...$parameters)
 * @method static Builder|Company whereName($value)
 * @property string $contract
 * @property string $payment_terms
 * @property int $responsible_id
 * @property int $national_manager_id
 * @property int $manager_id
 * @method static Builder|Company whereContract($value)
 * @method static Builder|Company whereManagerId($value)
 * @method static Builder|Company whereNationalManagerId($value)
 * @method static Builder|Company wherePaymentTerms($value)
 * @method static Builder|Company whereResponsibleId($value)
 * @property-read StadaManager|null $manager
 * @property-read StadaManager|null $national_manager
 * @property-read StadaManager|null $responsible
 * @property-read Collection|User[] $users
 * @property-read int|null $users_count
 * @method static Builder|Company whereDeliveryMethod($value)
 * @property-read Collection|Address[] $addresses
 * @property-read int|null $addresses_count
 * @property DeliveryMethodEnum $delivery_method
 */
class Company extends Model
{
    use HasFactory;

    protected $casts = [
        'delivery_method' => DeliveryMethodEnum::class
    ];

    public $timestamps = false;

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function responsible()
    {
        return $this->belongsTo(StadaManager::class, 'responsible_id');
    }

    public function national_manager()
    {
        return $this->belongsTo(StadaManager::class, 'national_manager_id');
    }

    public function manager()
    {
        return $this->belongsTo(StadaManager::class, 'manager_id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
