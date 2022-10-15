<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Address
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $company_id
 * @property string $address
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereId($value)
 * @property int $is_default
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereIsDefault($value)
 */
class Address extends Model
{
    use HasFactory;

    public $timestamps = false;

    public static function default(): Address
    {
        return self::whereIsDefault(true)
            ->first();
    }
}
