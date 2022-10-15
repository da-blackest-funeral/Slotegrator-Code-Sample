<?php

namespace App\Services\Notifications\Strategies;

use App\Interfaces\NotificationStrategy;
use App\Models\User;
use App\Traits\CreatesTemporaryFiles;

abstract class MailNotificationStrategy implements NotificationStrategy
{
    use CreatesTemporaryFiles;

    public function formUsers(User $user): array
    {
        $users[] = $user->email;
        $managerEmail = $user->company->manager?->contact_data?->email;

        if ($managerEmail) {
            $users[] = $managerEmail;
        }

        return $users;
    }
}
