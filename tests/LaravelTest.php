<?php

namespace Bepsvpt\Blurhash\Tests;

use Bepsvpt\Blurhash\Facades\BlurHash;
use Illuminate\Foundation\Application;
use Intervention\Image\Image;
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
        $hash = match (PHP_OS_FAMILY) {
            'Darwin', 'Windows' => 'LITR[|$*hK%g%2j[e.jZhef6d=g3',
            default => 'LITR[|$*hK%g%2j[e.jZhef6d=g3',
        };

        $this->assertSame(
            $hash,
            BlurHash::encode(__DIR__ . '/images/5.png'),
        );

        $this->assertInstanceOf(
            Image::class,
            BlurHash::decode($hash, 32, 32),
        );
    }
}
