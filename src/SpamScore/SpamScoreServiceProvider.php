<?php

namespace EBethus\SpamScore;

use Illuminate\Support\ServiceProvider;

class SpamScoreServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SpamScore::class, function ($app) {
            return new SpamScore(config('spamscore'));
        });

        $this->mergeConfigFrom(
            __DIR__.'/spamscore.php',
            'spamscore'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/spamscore.php' => config_path('spamscore.php'),
        ]);
    }
}