<?php

namespace Bepsvpt\Blurhash\Tests;

use Bepsvpt\Blurhash\BlurHash;
use Bepsvpt\Blurhash\Facades\BlurHash as BlurHashFacade;
use GdImage;
use Imagick;
use Jcupitt\Vips\Config;
use Jcupitt\Vips\DebugLogger;
use Jcupitt\Vips\Image;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase;

class BlurHashTest extends TestCase
{
    use WithWorkbench;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (in_array('--debug', $_SERVER['argv'], true)) {
            Config::setLogger(new DebugLogger);
        }
    }

    protected function file(string $name): string
    {
        return __DIR__.'/images/'.$name;
    }

    public function test_gd_driver_encode(): void
    {
        $path = $this->file('01.jpg');

        $blurhash = new BlurHash('gd');

        $this->assertSame(
            'L8Am^~jG00xu_NR*4TtQ.8R%IUkD',
            $blurhash->encode($path),
        );

        $blurhash->setMaxSize(256);

        $this->assertSame(
            'L7A^j{V@00%M_NRj4Txu.8RjIUkD',
            $blurhash->encode($path),
        );

        $blurhash->setMaxSize(128);

        $blurhash->setComponentX(9);

        $blurhash->setComponentY(5);

        $this->assertSame(
            'iDA-0Mae00xu_NIU4nxu?b.8WBDit7-;RjIUt7xu%MWBM{kCo0j[s;aeRjRPjZofjZkBkCaxjsWB%gj]M{jZt7WWWBoJkC',
            $blurhash->encode($path),
        );
    }

    public function test_imagick_driver_encode(): void
    {
        $path = $this->file('01.jpg');

        $blurhash = new BlurHash('imagick');

        $this->assertSame(
            'L7BND]jF00x]_NRj4Txt.8RjIUo#',
            $blurhash->encode($path),
        );

        $blurhash->setMaxSize(256);

        $this->assertSame(
            'L7BDsRVt00%M_NRj4Txu.8RjIUkD',
            $blurhash->encode($path),
        );

        $blurhash->setMaxSize(128);

        $blurhash->setComponentX(9);

        $blurhash->setComponentY(5);

        $hash = match (PHP_OS_FAMILY) {
            'Darwin' => 'iDBND^ae00xu~qIU4nx]?b.8WB9Ft7-;RjIUt6xu%MWBM{kCjYj[s;WBRjRPjYofjZfkkCf5jZWB%gkCM{aeozbIWBjZfk',
            default => 'iDBND^ae00xu~qIU4nx]?b.8WB9Ft7-;RjIUt6xu%MWBM{kCjYj[s;WBRjRPjYoyjZfkkCf5jZWB%gkCM{aeozbIWBjZfk',
        };

        $this->assertSame($hash, $blurhash->encode($path));
    }

    public function test_php_vips_driver_encode(): void
    {
        $path = $this->file('01.jpg');

        $blurhash = new BlurHash('php-vips');

        $this->assertSame(
            'L8AwbmjG00xu_NR*4TtQ.8R%IUkD',
            $blurhash->encode($path),
        );

        $blurhash->setMaxSize(256);

        $this->assertSame(
            'L7B45kV@00%M_NRj4Txu.8RjIUkD',
            $blurhash->encode($path),
        );

        $blurhash->setMaxSize(128);

        $blurhash->setComponentX(9);

        $blurhash->setComponentY(5);

        $hash = match (PHP_OS_FAMILY) {
            'Darwin' => 'iDA-0Mae00xu_NIU4nxu?b.8WBDit7-;RjIUt7xu%MWBM{kCo0j[s;aeRjRPjZofjZkBkCaxjsWB%gj]M{jZt7WVWBoKkC',
            default => 'iDA-0Nae00xu_NIU4nxu?b.8WB9Ft7-;RjIUt7xu%MWBM{kCjsj[s;aeRjRPjZofjZkCkCaxjsWB%gj]M{jZt7WVWBoKkC',
        };

        $this->assertSame(
            $hash,
            $blurhash->encode($path),
        );
    }

    public function test_different_formats_encode(): void
    {
        $hash = new BlurHash('gd');

        $this->assertSame(
            'LdIiLaD+%Mt75xIUoHWBR2oIV=WB',
            $hash->encode($this->file('02.gif'))
        );

        $this->assertSame(
            'L8Am^~jG00xu_NR*4TtQ.8R%IUkD',
            $hash->encode($this->file('03.png'))
        );

        $this->assertSame(
            'L8Am^~jG00xu_NR*4TtQ.8R%IUkD',
            $hash->encode($this->file('04.webp'))
        );

        $this->assertSame(
            'LvTQfPrXe9rr%Miwenj[hee9ene.',
            $hash->encode($this->file('06.png'))
        );

        $hash = match (PHP_OS_FAMILY) {
            'Darwin' => 'LXTRc+xGd=wJ.8i^enj]hee9e.f6',
            default => 'LYTRZxs:d=r?.8i_enkChee9e.f6',
        };

        $this->assertSame(
            $hash,
            (new BlurHash('imagick'))->encode($this->file('06.png'))
        );

        $hash = match (PHP_OS_FAMILY) {
            'Darwin' => 'LeTRKIsAe9sT-;i_enkChee9ene.',
            default => 'LdTRKIs9e9sA-;i_enkChee9ene.',
        };

        $this->assertSame(
            $hash,
            (new BlurHash('php-vips'))->encode($this->file('06.png'))
        );
    }

    /**
     * @requires PHP >= 8.1.0
     */
    public function test_avif_format_encode(): void
    {
        $hash = new BlurHash('gd');

        $this->assertSame(
            'L8Am^~jG00xu_NR*4TtQ.8R%IUkD',
            $hash->encode($this->file('05.avif'))
        );
    }

    public function test_gd_driver_decode(): void
    {
        $image = (new BlurHash('gd'))->decode(
            'rDBDsRV@00xu_NIU4nx]?b.8WBDit7-;RjIUxaxux]WBM{kCjZj[s;WBRjRPjZofjZkBkCaxjZWB%gkCM{jZt7WCWBj?j[Mxj[kCa|kCoLj[WBR*',
            300,
            200,
        );

        $this->assertInstanceOf(GdImage::class, $image);

        $fp = tmpfile();

        $this->assertIsResource($fp);

        imagejpeg($image, $fp);

        $path = stream_get_meta_data($fp)['uri'];

        $this->assertSame(md5_file($this->file('10.jpg')), md5_file($path));
    }

    public function test_imagick_driver_decode(): void
    {
        $image = (new BlurHash('imagick'))->decode(
            'rDBDsRV@00xu_NIU4nx]?b.8WBDit7-;RjIUxaxux]WBM{kCjZj[s;WBRjRPjZofjZkBkCaxjZWB%gkCM{jZt7WCWBj?j[Mxj[kCa|kCoLj[WBR*',
            300,
            200,
        );

        $this->assertInstanceOf(Imagick::class, $image);

        $fp = tmpfile();

        $this->assertIsResource($fp);

        $path = stream_get_meta_data($fp)['uri'];

        $this->assertTrue($image->writeImage('jpg:'.$path));

        $this->assertSame('305ada74d76f6ed94ad743659abe2a29', md5_file($path));
    }

    public function test_php_vips_driver_decode(): void
    {
        $image = (new BlurHash('php-vips'))->decode(
            'rDBDsRV@00xu_NIU4nx]?b.8WBDit7-;RjIUxaxux]WBM{kCjZj[s;WBRjRPjZofjZkBkCaxjZWB%gkCM{jZt7WCWBj?j[Mxj[kCa|kCoLj[WBR*',
            300,
            200,
        );

        $this->assertInstanceOf(Image::class, $image);

        $fp = tmpfile();

        $this->assertIsResource($fp);

        $path = stream_get_meta_data($fp)['uri'];

        $image->jpegsave($path);

        $this->assertSame(
            md5_file($this->file('12.jpg')),
            md5_file($path),
        );
    }

    public function test_laravel_facade(): void
    {
        $this->assertSame(
            'L8Am^~jG00xu_NR*4TtQ.8R%IUkD',
            BlurHashFacade::encode($this->file('01.jpg')),
        );
    }
}
