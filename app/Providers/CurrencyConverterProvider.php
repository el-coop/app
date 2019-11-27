<?php

namespace App\Providers;

use App\Services\CurrencyConverter;
use Illuminate\Support\ServiceProvider;

class CurrencyConverterProvider extends ServiceProvider {
    
    protected $defer = true;
    
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton(CurrencyConverter::class, function ($app) {
            return new CurrencyConverter();
        });
    }
    
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [CurrencyConverter::class];
    }
}
