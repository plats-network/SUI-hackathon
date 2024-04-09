<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->bootDirectives();
    }

    /**
     * Boot the package directives.
     *
     * @return void
     */
    protected function bootDirectives()
    {
        //uploadFileJS
        Blade::directive(
            'uploadFileJS',
            function () {
                return "<?php echo view('components.uploadfile.js'); ?>";
            }
        );
        //uploadFileCSS
        Blade::directive(
            'uploadFileCSS',
            function () {
                return "<?php echo view('components.uploadfile.css'); ?>";
            }
        );
    }
}
