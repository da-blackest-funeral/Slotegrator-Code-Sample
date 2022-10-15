<?php

namespace App\Enums;

enum CatalogSortFieldsEnum: string
{
    case REFERRAL = 'referral';

    case CATEGORY = 'category';

    case MANUFACTURER = 'manufacturer';

    case COUNTRY = 'country';

    public function getColumn(): string
    {
        return match ($this) {
            self::REFERRAL => 'referrals.name',
            self::CATEGORY => 'categories.name',
            self::MANUFACTURER => 'manufacturers.name',
            self::COUNTRY => 'countries.name',
        };
    }
}
