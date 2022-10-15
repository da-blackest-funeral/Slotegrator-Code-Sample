<?php

namespace App\Models;

use App\Casts\ContactDataCast;
use App\DTO\ContactDataDto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StadaManager
 *
 * @property int $id
 * @property int|null $user_id
 * @property ContactDataDto|null $contact_data
 * @method static \Database\Factories\StadaManagerFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|StadaManager newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StadaManager newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StadaManager query()
 * @method static \Illuminate\Database\Eloquent\Builder|StadaManager whereContactData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StadaManager whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StadaManager whereUserId($value)
 * @mixin \Eloquent
 */
class StadaManager extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'contact_data' => ContactDataCast::class,
    ];
}
