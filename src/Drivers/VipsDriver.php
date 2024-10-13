<?php

declare(strict_types=1);

namespace Bepsvpt\Blurhash\Drivers;

use Bepsvpt\Blurhash\Exceptions\DriverNotFoundException;
use Bepsvpt\Blurhash\Exceptions\UnableToCreateImageException;
use Bepsvpt\Blurhash\Exceptions\UnableToSetPixelException;
use Bepsvpt\Blurhash\Exceptions\UnsupportedFileException;
use Jcupitt\Vips\BandFormat;
use Jcupitt\Vips\Exception;
use Jcupitt\Vips\Extend;
use Jcupitt\Vips\Image;
use Jcupitt\Vips\Interpretation;

class VipsDriver extends Driver
{
    public Image $image;

    /**
     * @var array<int, int<0, 255>>
     */
    protected array $pixels = [];

    protected string $memory = '';

    /**
     * Create a new VipsDriver instance.
     *
     * @throws DriverNotFoundException
     */
    public function __construct(int $maxSize)
    {
        if (! class_exists(Image::class)) {
            throw new DriverNotFoundException(
                'php-vips is not loaded.',
            );
        }

        parent::__construct($maxSize);
    }

    /**
     * {@inheritdoc}
     *
     * @throws UnsupportedFileException
     */
    public function read(string $path): Image
    {
        try {
            $this->pixels = [];

            return Image::newFromFile($path);
        } catch (Exception $e) {
            throw new UnsupportedFileException(
                sprintf('The "%s" is not a supported image file. (%s)', $path, $e->getMessage()),
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param  Image  $image
     */
    public function resize(object $image): Image
    {
        [$width, $height] = $this->size($image);

        $scale = $this->scale($width, $height);

        if ($scale <= 1.0) {
            $scale = 1.0;
        }

        try {
            if ($image->hasAlpha()) {
                $image = $image->flatten(['background' => [255, 255, 255]]);
            }

            return tap($image->resize(1 / $scale), function () {
                $this->pixels = [];
            });
        } catch (Exception) {
            return $image;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param  Image  $image
     */
    public function size(object $image): array
    {
        return [ // @phpstan-ignore-line
            $image->width,
            $image->height,
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @param  Image  $image
     */
    public function color(object $image, int $x, int $y): array|false
    {
        try {
            if (empty($this->pixels)) {
                $this->pixels = $image->writeToArray();
            }
        } catch (Exception) {
            return false;
        }

        $idx = $x * 3 + $this->width * $y * 3;

        return [ // @phpstan-ignore-line
            (int) $this->pixels[$idx],
            (int) $this->pixels[$idx + 1],
            (int) $this->pixels[$idx + 2],
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @throws UnableToCreateImageException
     */
    public function create(int $width, int $height): static
    {
        try {
            $this->image = Image::black(1, 1)
                ->add(255)
                ->cast(BandFormat::UCHAR)
                ->embed(0, 0, $width, $height, ['extend' => Extend::COPY])
                ->copy(['interpretation' => Interpretation::RGB])
                ->bandjoin([255, 255]);

            $this->memory = $this->image->writeToMemory();
        } catch (Exception) {
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
            $idx = $x * 3 + $this->image->width * $y * 3;

            $this->memory[$idx] = chr($color[0]);

            $this->memory[$idx + 1] = chr($color[1]);

            $this->memory[$idx + 2] = chr($color[2]);

            $this->image = Image::newFromMemory(
                $this->memory,
                $this->image->width,
                $this->image->height,
                $this->image->bands,
                $this->image->format,
            );
        } catch (Exception) {
            throw new UnableToSetPixelException;
        }

        return true;
    }
}
