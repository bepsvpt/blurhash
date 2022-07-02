<?php

namespace Bepsvpt\Blurhash\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string encode(mixed $data)
 * @method static \Intervention\Image\Image decode(string $blurhash, int $width, int $height)
 * @method static \Bepsvpt\Blurhash\BlurHash setComponentX(int $componentX)
 * @method static \Bepsvpt\Blurhash\BlurHash setComponentY(int $componentY)
 * @method static \Bepsvpt\Blurhash\BlurHash setResizedImageMaxWidth(int $imageWidth)
 */
class BlurHash extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'blurhash';
    }
}
