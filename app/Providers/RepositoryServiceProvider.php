<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\Administracion\UsuarioRepositoryInterface;
use App\Repositories\Administracion\UsuarioRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // =========================
        // Administración
        // =========================

        // Usuario
        $this->app->bind(
            UsuarioRepositoryInterface::class,
            UsuarioRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
