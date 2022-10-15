<?php

namespace App\Enums;

enum StatusEnum: int
{
    case REGISTRATION = 0; // оформление

    case COORDINATION = 1; // согласование

    case AGREED = 2; // согласован

    case WAREHOUSE = 3; // передан на склад

    case READY = 4; // готов к отгрузке

    case SHIPPED = 5; // отгружен

    case DECLINED = 6; // отменен
}
