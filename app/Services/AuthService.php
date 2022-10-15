<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Password;

class AuthService
{
    public function checkResetToken(?User $user, string $token): bool
    {
        return $user && Password::tokenExists($user, $token);
    }

    public function getUser(string $email): ?User
    {
        return User::whereEmail($email)
            ->first();
    }
}
