<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share current tenant with all views
        View::composer('*', function ($view) {
            if (app()->bound('current_tenant')) {
                $view->with('currentTenant', app('current_tenant'));
            }
        });
    }
}
