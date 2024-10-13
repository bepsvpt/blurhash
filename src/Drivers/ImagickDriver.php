<?php

declare(strict_types=1);

namespace Bepsvpt\Blurhash\Drivers;

use Bepsvpt\Blurhash\Exceptions\DriverNotFoundException;
use Bepsvpt\Blurhash\Exceptions\UnableToCreateImageException;
use Bepsvpt\Blurhash\Exceptions\UnableToSetPixelException;
use Bepsvpt\Blurhash\Exceptions\UnsupportedFileException;
use Imagick;
use ImagickDraw;
use ImagickDrawException;
use ImagickException;
use ImagickPixel;
use ImagickPixelException;

class ImagickDriver extends Driver
{
    public Imagick $image;

    /**
     * Create a new ImagickDriver instance.
     *
     * @throws DriverNotFoundException
     */
    public function __construct(int $maxSize)
    {
        if (! extension_loaded('imagick')) {
            throw new DriverNotFoundException(
                'Imagick extension is not loaded.',
            );
        }

        parent::__construct($maxSize);
    }

    /**
     * {@inheritdoc}
     *
     * @throws UnsupportedFileException
     */
    public function read(string $path): Imagick
    {
        try {
            return new Imagick($path);
        } catch (ImagickException) {
            throw new UnsupportedFileException(
                sprintf('The "%s" is not a supported image file.', $path),
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param  Imagick  $image
     */
    public function resize(object $image): Imagick
    {
        try {
            $image->adaptiveResizeImage(
                $this->maxSize,
                $this->maxSize,
                true,
            );

            $image->setImageAlphaChannel(Imagick::ALPHACHANNEL_BACKGROUND);

            $image->setImageBackgroundColor(
                new ImagickPixel('rgba(255, 255, 255, 0)'),
            );
        } catch (ImagickException) {
            // ignored
        }

        return $image;
    }

    /**
     * {@inheritdoc}
     *
     * @param  Imagick  $image
     *
     * @throws ImagickException
     */
    public function size(object $image): array
    {
        return [ // @phpstan-ignore-line
            $image->getImageWidth(),
            $image->getImageHeight(),
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @param  Imagick  $image
     */
    public function color(object $image, int $x, int $y): array|false
    {
        try {
            $color = $image->getImagePixelColor($x, $y)->getColor(2);

            return [
                max(min($color['r'], 255), 0),
                max(min($color['g'], 255), 0),
                max(min($color['b'], 255), 0),
            ];
        } catch (ImagickException|ImagickPixelException) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws UnableToCreateImageException
     */
    public function create(int $width, int $height): static
    {
        $this->image = new Imagick;

        try {
            $this->image->newImage(
                $width,
                $height,
                new ImagickPixel('transparent'),
            );
        } catch (ImagickException) {
            throw new UnableToCreateImageException;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws UnableToSetPixelException
     */
    public function pixel(int $x, int $y, array $color): bool
    {
        try {
            $rga = sprintf('rgb(%s,%s,%s)', ...$color);

            $draw = new ImagickDraw;

            $draw->setFillColor(new ImagickPixel($rga));

            $draw->point($x, $y);

            if ($this->image->drawImage($draw) === false) {
                throw new UnableToSetPixelException;
            }

            return true;
        } catch (ImagickException|ImagickDrawException|ImagickPixelException) {
            throw new UnableToSetPixelException;
        }
    }
}
