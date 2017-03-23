<?php

namespace Msonowal\Laraxchange\Providers;

use Illuminate\Support\ServiceProvider;
use Msonowal\Laraxchange\Console\CacheConversionRates;
use Msonowal\Laraxchange\Currency;

class CurrencyServiceProvider extends ServiceProvider
{
    //protected $defer = true;
    public function boot()
    {
        $this->publishConfiguration();
    }
    public function register()
    {
        $config = __DIR__ . '/../../config/currency.php';
        $this->mergeConfigFrom($config, 'currency');
        $this->app->singleton('Currency', Currency::class);

        if ($this->app->runningInConsole()) {
            $this->registerCommands();
        }
    }
    public function provides()
    {
        return ['Currency'];
    }
    public function publishConfiguration()
    {
        $path   =   realpath(__DIR__.'/../../config/currency.php');
        $this->publishes([$path => config_path('currency.php')], 'config');
    }
    public function registerCommands()
    {
        $this->commands([
            CacheConversionRates::class
        ]);
    }
}
