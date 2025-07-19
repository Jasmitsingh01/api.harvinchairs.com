<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Database\Models\User as APIUser;
use App\Models\User as WebUser;
use Illuminate\Auth\EloquentUserProvider;

class RouteBasedUserModelServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Check if the current route is an API route
        if (Route::is('api*')) {
            // Use App\Database\Models\User for API routes
            $this->setUserProvider(APIUser::class);
        } else {
            // Use App\Models\User for web routes
            $this->setUserProvider(WebUser::class);
        }
    }

    /**
     * Set the default user provider for authentication.
     *
     * @param string $modelClass
     * @return void
     */
    protected function setUserProvider(string $modelClass)
    {
        $this->app->extend('auth', function ($auth, $app) use ($modelClass) {
            $provider = new EloquentUserProvider($app['hash'], $modelClass);
            $auth->setProvider($provider);
            return $auth;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
