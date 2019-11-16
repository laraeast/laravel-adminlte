<?php

namespace Laraeast\AdminLte\Providers;

use Illuminate\Support\ServiceProvider;
use Laraeast\AdminLte\Console\Commands\AdminlteInstallCommand;

class AdminLteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AdminlteInstallCommand::class,
            ]);
        }
    }
}
