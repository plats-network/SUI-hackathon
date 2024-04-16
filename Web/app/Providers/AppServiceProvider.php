<?php

namespace App\Providers;

use App\View\Components\Base;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Required config menus
     *
     * @var bool
     */
    protected $bootedAppMenu = false;

    /**
     * Check booted composer
     *
     * @var bool
     */
    protected $bootedComposer = false;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.use_ssl', false)) {
            URL::forceScheme('https');
        }

        Blade::componentNamespace('App\View\Components\Forms', 'form');
        Blade::anonymousComponentNamespace('admin._components', 'admin');

        Blade::component('mails.base', Base::class);
        //09.12.2023
        //Version Website. Load From Env
        View::share('version', env('APP_VERSION', '2'));
    }

    /**
     * Get config files menu
     *
     * @return bool
     */
    protected function addMenuHelper(): bool
    {
        if ($this->bootedAppMenu || request()->expectsJson() || Auth::guest()) {
            return true;
        }

        $menuFiles = glob(app_path('Helpers/Menus/*.php'));
        foreach ($menuFiles as $file) {
            require $file;
        }

        $this->bootedAppMenu = true;

        return true;
    }
}
