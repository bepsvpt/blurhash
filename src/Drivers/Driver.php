<?php

declare(strict_types=1);

namespace Bepsvpt\Blurhash\Drivers;

use Bepsvpt\Blurhash\BlurHash;
use Bepsvpt\Blurhash\Exceptions\UnableToGetColorException;
use Jcupitt\Vips\Image;

abstract class Driver
{
    /**
     * Image width.
     */
    public int $width;

    /**
     * Image height.
     */
    public int $height;

    /**
     * Create a new driver instance.
     *
     * @param  positive-int  $maxSize
     */
    public function __construct(
        public int $maxSize,
    ) {
        //
    }

    /**
     * @return array<int, array<int, array{
     *     0: float,
     *     1: float,
     *     2: float,
     * }>>
     *
     * @throws UnableToGetColorException
     */
    public function colors(string $path): array
    {
        $origin = $this->read($path);

        $image = $this->resize($origin);

        [$this->width, $this->height] = $this->size($image);

        $colors = $cache = [];

        for ($x = 0; $x < $this->width; $x++) {
            for ($y = 0; $y < $this->height; $y++) {
                $rgb = $this->color($image, $x, $y);

                if ($rgb === false) {
                    throw new UnableToGetColorException(
                        sprintf('Unable to get color at x: %d, y: %d.', $x, $y),
                    );
                }

                $key = sprintf('%d-%d-%d', $rgb[0], $rgb[1], $rgb[2]);

                if (! isset($cache[$key])) {
                    $cache[$key] = [
                        BlurHash::$rgbToLinearMap[$rgb[0]],
                        BlurHash::$rgbToLinearMap[$rgb[1]],
                        BlurHash::$rgbToLinearMap[$rgb[2]],
                    ];
                }

                $colors[$x][$y] = $cache[$key];
            }
        }

        return $colors;
    }

    /**
     * Get resizing factor.
     *
     * @param  positive-int  $width
     * @param  positive-int  $height
     */
    public function scale(int $width, int $height): float
    {
        return max(
            $width / $this->maxSize,
            $height / $this->maxSize,
            1.0,
        );
    }

    /**
     * Reads an image from a path and returns an image resource based on the driver.
     */
    abstract public function read(string $path): object;

    /**
     * Resizes the image to a smaller version.
     */
    abstract public function resize(object $image): object;

    /**
     * Retrieves the image's width and height.
     *
     * @return array{
     *     0: positive-int,
     *     1: positive-int,
     * }
     */
    abstract public function size(object $image): array;

    /**
     * Obtains the color at a specific position.
     *
     * @return array{
     *     0: int<0, 255>,
     *     1: int<0, 255>,
     *     2: int<0, 255>,
     * }|false
     */
    abstract public function color(object $image, int $x, int $y): array|false;

    /**
     * Creates a new image with specified width and height.
     *
     * @param  positive-int  $width
     * @param  positive-int  $height
     */
    abstract public function create(int $width, int $height): static;

    /**
     * Applies a color at a specific position.
     *
     * @param  non-negative-int  $x
     * @param  non-negative-int  $y
     * @param array{
     *     0: int<0, 255>,
     *     1: int<0, 255>,
     *     2: int<0, 255>,
     * } $color
     */
    abstract public function pixel(int $x, int $y, array $color): bool;
}
