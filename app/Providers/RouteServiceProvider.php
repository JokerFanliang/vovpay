<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapAdminRoutes();

        $this->mapAgentRoutes();

        $this->mapUserRoutes();

        $this->mapPayRoutes();

        $this->mapAppPayRoutes();

        $this->mapCourtRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace('App\Http\Home\Controllers')
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    protected function mapAgentRoutes()
    {
        Route::prefix('agent')
            ->middleware('web')
            ->namespace('App\Http\Agent\Controllers')
            ->group(base_path('routes/agent.php'));
    }

    protected function mapAdminRoutes()
    {
        Route::prefix('admin')
            ->middleware('web')
            ->namespace('App\Http\Admin\Controllers')
            ->group(base_path('routes/admin.php'));
    }

    protected function mapUserRoutes()
    {
        Route::prefix('user')
            ->middleware('web')
            ->namespace('App\Http\User\Controllers')
            ->group(base_path('routes/user.php'));
    }

    protected function mapPayRoutes()
    {
        Route::prefix('pay')
            ->middleware('web')
            ->namespace('App\Http\Pay\Controllers')
            ->group(base_path('routes/pay.php'));
    }

    protected function mapAppPayRoutes()
    {
        Route::prefix('appPay')
            ->middleware('web')
            ->namespace('App\Http\Pay\Controllers')
            ->group(base_path('routes/apay.php'));
    }

    protected function mapCourtRoutes()
    {
        Route::prefix('court')
            ->middleware('web')
            ->namespace('App\Http\Court\Controllers')
            ->group(base_path('routes/court.php'));
    }
}
