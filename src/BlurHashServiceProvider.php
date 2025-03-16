<?php

namespace Bepsvpt\Blurhash;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class BlurHashServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->configPath() => config_path('blurhash.php'),
            ], 'config');
        }
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->configPath(), 'blurhash');

        $this->app->singleton('blurhash', function (Application $app) {
            $config = $app->make('config')->get('blurhash');

            return new BlurHash(
                $config['driver'] ?? 'gd', // @phpstan-ignore-line
                $config['components-x'], // @phpstan-ignore-line
                $config['components-y'], // @phpstan-ignore-line
                $config['resized-max-size'] ?? $config['resized-image-max-width'], // @phpstan-ignore-line
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
