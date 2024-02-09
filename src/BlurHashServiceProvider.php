<?php

namespace Bepsvpt\Blurhash;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application;

class BlurHashServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot(): void
    {
        if ($this->app instanceof Application) {
            $this->bootLumen();
        } else {
            $this->bootLaravel();
        }
    }

    /**
     * Bootstrap laravel application events.
     */
    protected function bootLaravel(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->configPath() => config_path('blurhash.php'),
            ], 'config');
        }
    }

    /**
     * Bootstrap lumen application events.
     */
    protected function bootLumen(): void
    {
        $this->app->configure('blurhash');
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->configPath(), 'blurhash');

        $this->app->singleton('blurhash', function ($app) {
            $config = $app['config']->get('blurhash');

            return new BlurHash(
                $config['driver'] ?? 'gd',
                $config['components-x'],
                $config['components-y'],
                $config['resized-max-size'] ?? $config['resized-image-max-width'],
            );
        });
    }

    /**
     * Get config file path.
     */
    protected function configPath(): string
    {
        return __DIR__.'/../config/blurhash.php';
    }
}
