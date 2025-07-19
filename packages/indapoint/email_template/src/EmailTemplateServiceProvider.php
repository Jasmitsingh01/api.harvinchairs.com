<?php

namespace Indapoint\EmailTemplate;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class EmailTemplateServiceProvider extends ServiceProvider
{

    /**
     * This will be used to register configuration
     *
     * @var  string
     */
    protected $packageName = 'email_template';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Regiter migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Publish your config
        $this->publishes(
                [__DIR__ . '/config/email_template.php' => config_path($this->packageName . '.php')],
            'config'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        // Merge config file
        if (file_exists(base_path() . '/config/email_template.php')) {
            $this->mergeConfigFrom(base_path() . '/config/email_template.php', $this->packageName);
        } else {
            $this->mergeConfigFrom(__DIR__ . '/config/email_template.php', $this->packageName);
        }

        // Load routes
        Route::group(['prefix' => 'api'], function ($router) {
            // By default, use the routes file provided in vendor
            $routeFilePathInUse = __DIR__ . '/routes/routes.php';

            // but if there's a file with the same name in routes/backpack, use that one
            if (file_exists(base_path() . '/config/email_template.php') && file_exists(base_path() . '/routes/email_template.php')) {
                $routeFilePathInUse = base_path() . '/routes/email_template.php';
            }

            require $routeFilePathInUse;
        });

        $this->app->make('Indapoint\EmailTemplate\App\Http\Controllers\EmailTemplateController');
    }
}
