<?php

namespace Bepsvpt\Blurhash\Tests;

use Bepsvpt\Blurhash\Facades\BlurHash;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase;

final class LaravelTest extends TestCase
{
    /**
     * Get package providers.
     *
     * @param Application  $app
     *
     * @return array<string>
     */
    protected function getPackageProviders($app)
    {
        return ['Bepsvpt\BlurHash\BlurHashServiceProvider'];
    }

    /**
     * Get package aliases.
     *
     * @param Application $app
     *
     * @return array<string>
     */
    protected function getPackageAliases($app)
    {
        return [
            'BlurHash' => 'Bepsvpt\Blurhash\Facades\BlurHash'
        ];
    }

    public function testPackageLoaded(): void
    {
        $config = $this->app['config'];

        $this->assertArrayHasKey('blurhash', $config);

        $this->assertNotEmpty($config['blurhash']);

        $this->assertSame(
            'LITbcr$*hK%g%2j[e.jZhef6d=g3',
            BlurHash::encode(__DIR__.'/images/5.png')
        );
    }
}
