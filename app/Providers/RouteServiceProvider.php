<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->routes(function () {
            if (file_exists(base_path('routes/web.php'))) {
                require base_path('routes/web.php');
            }

            if (file_exists(base_path('routes/api.php'))) {
                require base_path('routes/api.php');
            }
        });
    }
}
