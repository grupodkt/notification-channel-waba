<?php
namespace NotificationChannels\Waba;

use Illuminate\Support\ServiceProvider;

class WabaProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(WabaChannel::class)
            ->needs(Waba::class)
            ->give(function () {
                return new Waba(
                    $this->app->make(WabaConfig::class)
                );
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind(WabaConfig::class, function () {
            return new WabaConfig($this->app['config']['services.waba']);
        });
    }
}
