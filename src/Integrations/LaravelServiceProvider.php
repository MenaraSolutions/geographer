<?php

namespace MenaraSolutions\Geographer\Integrations;

use Illuminate\Support\ServiceProvider;
use MenaraSolutions\Geographer\Earth;

/**
 * Class LaravelServiceProvider
 * @package Dusterio\LinkPreview\Integrations
 * @codeCoverageIgnore
 */
class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('geographer', function() {
            return new Earth();
        });
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['geographer'];
    }
}
