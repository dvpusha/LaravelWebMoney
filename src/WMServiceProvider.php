<?php

namespace Pusha\LaravelWebMoney;

use Illuminate\Support\ServiceProvider;

class WMServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('webmoney.php')
        ]);
    }
}