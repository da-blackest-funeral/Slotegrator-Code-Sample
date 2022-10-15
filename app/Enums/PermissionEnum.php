<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case EDIT_USERS = 'edit_users';

    case COMMENT_IN_ORDERS = 'comment_in_orders';
}
