<?php

namespace App\Enums;

enum OrderTypeEnum: string
{
    case WITH_AUCTIONS = 'zdom';

    case WITHOUT_AUCTIONS = 'zdlo';
}
