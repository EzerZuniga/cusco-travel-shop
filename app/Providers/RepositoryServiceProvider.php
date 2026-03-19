<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\TourRepositoryInterface;
use App\Repositories\TourRepository;
use App\Repositories\Contracts\ReservaRepositoryInterface;
use App\Repositories\ReservaRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Registrar bindings de repositorios
        $this->app->bind(TourRepositoryInterface::class, TourRepository::class);
        $this->app->bind(ReservaRepositoryInterface::class, ReservaRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
