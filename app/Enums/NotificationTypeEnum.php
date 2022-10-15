<?php

namespace App\Enums;

enum NotificationTypeEnum
{
    case MAIL_ORDER_CREATED;

    case DATABASE_ORDER_CREATED;

    case MAIL_ORDER_STATUS_CHANGED;

    case DATABASE_ORDER_STATUS_CHANGED;
}
