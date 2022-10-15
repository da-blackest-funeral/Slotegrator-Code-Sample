<?php

namespace App\Enums;

enum RolesEnum: string
{
    case ADMIN = 'admin';

    case DISTRIBUTOR = 'distributor';

    case MAIN_DISTRIBUTOR = 'main_distributor';

    case MANAGER = 'manager';

    case ACCOUNT_MANAGER = 'account_manager';
}
