<?php

namespace nattaponra\sociallara;

use Illuminate\Support\ServiceProvider;

class SocialLaraServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

         $this->loadMigrationsFrom(__DIR__.'/database/migrations');

         $this->publishes([__DIR__.'/config/config.php' => config_path('sociallara.php'),]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('sociallara', function () {
            return new SocialLara(new ProviderFactory());
        });
    }
}
