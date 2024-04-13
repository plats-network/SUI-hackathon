<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     *
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // Local: http://cws-colosseum.plats.test
            // Dev: https://dev-cws.plats.network
            // Prod: https://cws.plats.network
            $this->mapCwsRoute(ENV('APP_URL'));

            // Local: http://event.plats.test
            // Dev: https://dev-event.plats.network
            // Prod: https://event.plats.network
            $this->mapEventRoute(ENV('APP_URL'));

            // Local: http://api.plats.test
            // Dev: https://dev-api.plats.network
            // Prod: https://api.plats.network
            $this->mapApiRoute(ENV('APP_URL'));

            // Local: http://minigame.plats.test
            // Dev: https://dev-minigame.plats.network
            // Prod: https://minigame.plats.network
            $this->mapGameRoute(ENV('APP_URL'));

            // Prod: https://colosseum.plats.network
            $this->mapColosseumRoute(ENV('APP_URL'));
            $this->mapWebRoute();
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    protected function mapApiRoute($domain)
    {
        Route::domain(ENV('SUB_API').'.'.$domain)
            ->middleware(['api'])
            ->prefix('api')
            ->group(base_path('routes/api.php'));
    }

    protected function mapCwsRoute($domain)
    {
        Route::domain(ENV('SUB_CWS').'.'.$domain)
            ->middleware(['web'])
            ->group(base_path('routes/admin.php'));
    }

    protected function mapEventRoute($domain)
    {
        Route::domain(ENV('SUB_EVENT').'.'.$domain)
            ->middleware(['web'])
            ->group(base_path('routes/web.php'));
    }

    protected function mapGameRoute($domain)
    {
        Route::domain(ENV('SUB_MINIGAME').'.'.$domain)
            ->middleware(['web'])
            ->group(base_path('routes/game.php'));
    }
    protected function mapColosseumRoute($domain)
    {
        Route::domain(ENV('SUB_COLOSSEUM').'.'.$domain)
            ->middleware(['web'])
            ->group(base_path('routes/web.php'));
    }
    protected function mapWebRoute()
    {
        Route::middleware(['web'])
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }
}
