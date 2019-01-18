<?php

namespace Geoff\EasyAllinpay;

use Illuminate\Support\ServiceProvider;

class EasyAllinpayServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->publishes([
            __DIR__.'/config/easyallinpay.php' => config_path('easyallinpay.php'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('easyallinpay', function ($app){
            return new EasyAllinpay($app['session'], $app['config']);
        });
    }

    public function provides()
    {
        return ['easyallinpay'];
    }
}
