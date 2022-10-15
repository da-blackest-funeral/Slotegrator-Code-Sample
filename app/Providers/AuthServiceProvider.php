<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('update-user', function (User $user, User $target) {
            return $user->hasPermissionTo('edit_users') &&
                $user->company_id == $target->company_id ||
                $target->id == $user->id;
        });

        Gate::define('delete-user', function (User $user, User $target) {
            return $user->hasPermissionTo('edit_users') &&
                $user->company_id == $target->company_id &&
                $target->id != $user->id;
        });
    }
}
