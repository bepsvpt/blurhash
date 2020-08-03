<?php

namespace Bepsvpt\Blurhash\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string encode(mixed $data)
 * @method static \Bepsvpt\Blurhash\BlurHash setComponentX(int $componentX)
 * @method static \Bepsvpt\Blurhash\BlurHash setComponentY(int $componentY)
 * @method static \Bepsvpt\Blurhash\BlurHash setResizedImageMaxWidth(int $imageWidth)
 */
final class BlurHash extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'blurhash';
    }
}
