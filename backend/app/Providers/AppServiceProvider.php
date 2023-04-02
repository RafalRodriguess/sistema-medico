<?php

namespace App\Providers;

use App\Http\ViewComposers\ComercialNavbarComposer;
use App\Http\ViewComposers\InstituicaoNavbarComposer;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Laravel\Sanctum\Sanctum;
use Livewire\LivewireBladeDirectives;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Sanctum::ignoreMigrations();

        View::composer('comercial.partials.navbar', ComercialNavbarComposer::class);

        View::composer('instituicao.partials.navbar', InstituicaoNavbarComposer::class);

        Blade::directive('this', [LivewireBladeDirectives::class, 'this']);
    }
}
