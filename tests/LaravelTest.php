<?php

namespace Bepsvpt\Blurhash\Tests;

use Bepsvpt\Blurhash\Facades\BlurHash;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase;

class LaravelTest extends TestCase
{
    /**
     * Get package providers.
     *
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return ['Bepsvpt\Blurhash\BlurHashServiceProvider'];
    }

    /**
     * Get package aliases.
     *
     * @param  Application  $app
     * @return array<string, class-string>
     */
    protected function getPackageAliases($app): array
    {
        return [
            'BlurHash' => 'Bepsvpt\Blurhash\Facades\BlurHash',
        ];
    }

    public function testPackageLoaded(): void
    {
        BlurHash::decode('LITbcr$*hK%g%2j[e.jZhef6d=g3', 360, 360);

        $this->assertSame(
            'LITbcr$*hK%g%2j[e.jZhef6d=g3',
            BlurHash::encode(__DIR__ . '/images/5.png')
        );
    }
}
