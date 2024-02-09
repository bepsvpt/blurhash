<?php

namespace Bepsvpt\Blurhash\Facades;

use GdImage;
use Illuminate\Support\Facades\Facade;
use Imagick;
use Jcupitt\Vips\Image;

/**
 * @method static string encode(mixed $data)
 * @method static GdImage|Imagick|Image decode(string $blurhash, int $width, int $height)
 * @method static \Bepsvpt\Blurhash\BlurHash setComponentX(int $componentX)
 * @method static \Bepsvpt\Blurhash\BlurHash setComponentY(int $componentY)
 * @method static \Bepsvpt\Blurhash\BlurHash setMaxSize(int $maxSize)
 */
class BlurHash extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'blurhash';
    }
}
