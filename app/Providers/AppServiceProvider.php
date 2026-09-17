<?php

namespace App\Providers;

use App\Models\Permiso;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('permisos')) {
            Permiso::where('estado', 'ACTIVO')
                ->pluck('codigo')
                ->each(function ($codigo) {

                    Gate::define($codigo, function ($user) use ($codigo) {

                        return $user->tienePermiso($codigo);
                    });
                });
        }
    }
}
