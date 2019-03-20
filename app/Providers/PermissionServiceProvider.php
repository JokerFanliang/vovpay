<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Permission\UserPermissionServer;
use App\Services\Permission\GoogleAuthenticator;

class PermissionServiceProvider extends ServiceProvider
{
    protected $defer = true;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton('permission', function () {
            return new UserPermissionServer();
        });
        $this->app->singleton('googleauth', function () {
            return new GoogleAuthenticator();
        });
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['permission','googleauth'];
    }
}
