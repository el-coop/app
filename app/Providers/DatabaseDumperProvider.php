<?php

namespace App\Providers;

use App\Services\Database\DatabaseDumperContract;
use App\Services\Database\MysqlDumper;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DatabaseDumperProvider extends ServiceProvider implements DeferrableProvider {
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton(DatabaseDumperContract::class, function ($app) {
            return new MysqlDumper;
        });
    }

    public function provides() {
        return [DatabaseDumperContract::class];
    }
}
