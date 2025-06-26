<?php

namespace Unsplash\Laravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Unsplash\Laravel\UnsplashService; // This will be created next

class UnsplashServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/unsplash.php' => config_path('unsplash.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/unsplash.php',
            'unsplash'
        );

        $this->app->singleton(UnsplashService::class, function ($app) {
            $config = $app['config']['unsplash'];
            return new UnsplashService(
                $config['application_id'],
                $config['utm_source'],
                $app['cache']->store($config['cache_store'] ?? null), // Use null for default store
                $config['cache_duration'] ?? 60, // Default to 60 minutes
                $config['default_random_photo_options'] ?? [] // Default to empty array
            );
        });

        $this->app->alias(UnsplashService::class, 'unsplash');
    }

    public function provides()
    {
        return [UnsplashService::class, 'unsplash'];
    }
}
