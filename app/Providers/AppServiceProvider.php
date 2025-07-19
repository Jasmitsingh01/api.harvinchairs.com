<?php

namespace App\Providers;

use App\Models\SiteConfiguration;
use App\Payments\Payment;
use App\Database\Models\Settings;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('payment', function ($app) {
            $settings = Settings::first();
            $active_payment_gateway =  $settings->options['paymentGateway'] ?? ACTIVE_PAYMENT_GATEWAY;
            $gateway = 'App\\Payments\\' . ucfirst($active_payment_gateway);
            return new Payment($app->make($gateway));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Password::defaults(fn () => Password::min(8)->mixedCase());

        $mail_password = SiteConfiguration::where('varname', 'MAIL_PASSWORD')->value('value');
        $config = [
            'transport' => 'smtp',
            'host' => SiteConfiguration::where('varname', 'MAIL_HOST')->value('value') ?? config('mail.mailers.smtp.host'),
            'port' => SiteConfiguration::where('varname', 'MAIL_PORT')->value('value') ?? config('mail.mailers.smtp.port'),
            'encryption' => SiteConfiguration::where('varname', 'MAIL_ENCRYPTION')->value('value') ?? config('mail.mailers.smtp.encryption'),
            'username' => SiteConfiguration::where('varname', 'MAIL_USERNAME')->value('value') ?? config('mail.mailers.smtp.username'),
            'password' => $mail_password ? decrypt($mail_password) : config('mail.mailers.smtp.password'),
        ];

        Config::set('mail.mailers.smtp', $config);
        Config::set('mail.from.address', SiteConfiguration::where('varname', 'MAIL_FROM_ADDRESS')->value('value') ?? config('mail.from.address'));
        Config::set('mail.from.name', SiteConfiguration::where('varname', 'MAIL_FROM_NAME')->value('value') ?? config('mail.from.name'));
        // DB::listen(function ($query) {
        //     Log::info($query->sql);
        // });
    }
}
