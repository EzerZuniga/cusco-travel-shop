<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Usuario;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            if (isset($user->is_admin) && $user->is_admin) {
                return true;
            }
            return null;
        });

        Gate::define('admin', function (Usuario $user) {
            if (isset($user->is_admin) && $user->is_admin) {
                return true;
            }
            if (isset($user->rol) && strtolower($user->rol) === 'admin') {
                return true;
            }
            $adminEmail = config('app.admin_email');
            if ($adminEmail && isset($user->email) && $user->email === $adminEmail) {
                return true;
            }
            return false;
        });

        Gate::define('manage-tours', function (Usuario $user) {
            if (isset($user->is_admin) && $user->is_admin) {
                return true;
            }
            if (isset($user->rol)) {
                $rol = strtolower($user->rol);
                return in_array($rol, ['manager', 'operador', 'gestor', 'admin']);
            }
            return false;
        });
    }
}
