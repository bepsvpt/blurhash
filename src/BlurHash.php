<?php

namespace Bepsvpt\Blurhash;

use GdImage;
use Intervention\Image\Constraint;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BlurHash
{
    /**
     * RGB value to linear map.
     *
     * Function calls and math calculation is expensive.
     * Thus, we hard code all possible transfer value.
     *
     * @var array<int, float>
     */
    protected static array $rgbToLinearMap = [
        0 => 0.0000,
        1 => 0.0003,
        2 => 0.0006,
        3 => 0.0009,
        4 => 0.0012,
        5 => 0.0015,
        6 => 0.0018,
        7 => 0.0021,
        8 => 0.0024,
        9 => 0.0027,
        10 => 0.0030,
        11 => 0.0033,
        12 => 0.0036,
        13 => 0.0040,
        14 => 0.0043,
        15 => 0.0047,
        16 => 0.0051,
        17 => 0.0056,
        18 => 0.0060,
        19 => 0.0065,
        20 => 0.0069,
        21 => 0.0074,
        22 => 0.0080,
        23 => 0.0085,
        24 => 0.0091,
        25 => 0.0097,
        26 => 0.0103,
        27 => 0.0109,
        28 => 0.0116,
        29 => 0.0122,
        30 => 0.0129,
        31 => 0.0137,
        32 => 0.0144,
        33 => 0.0152,
        34 => 0.0159,
        35 => 0.0168,
        36 => 0.0176,
        37 => 0.0185,
        38 => 0.0193,
        39 => 0.0202,
        40 => 0.0212,
        41 => 0.0221,
        42 => 0.0231,
        43 => 0.0241,
        44 => 0.0251,
        45 => 0.0262,
        46 => 0.0273,
        47 => 0.0284,
        48 => 0.0295,
        49 => 0.0307,
        50 => 0.0318,
        51 => 0.0331,
        52 => 0.0343,
        53 => 0.0356,
        54 => 0.0368,
        55 => 0.0382,
        56 => 0.0395,
        57 => 0.0409,
        58 => 0.0423,
        59 => 0.0437,
        60 => 0.0451,
        61 => 0.0466,
        62 => 0.0481,
        63 => 0.0497,
        64 => 0.0512,
        65 => 0.0528,
        66 => 0.0544,
        67 => 0.0561,
        68 => 0.0578,
        69 => 0.0595,
        70 => 0.0612,
        71 => 0.0630,
        72 => 0.0648,
        73 => 0.0666,
        74 => 0.0684,
        75 => 0.0703,
        76 => 0.0722,
        77 => 0.0742,
        78 => 0.0761,
        79 => 0.0781,
        80 => 0.0802,
        81 => 0.0822,
        82 => 0.0843,
        83 => 0.0865,
        84 => 0.0886,
        85 => 0.0908,
        86 => 0.0930,
        87 => 0.0953,
        88 => 0.0975,
        89 => 0.0998,
        90 => 0.1022,
        91 => 0.1046,
        92 => 0.1070,
        93 => 0.1094,
        94 => 0.1119,
        95 => 0.1144,
        96 => 0.1169,
        97 => 0.1195,
        98 => 0.1221,
        99 => 0.1247,
        100 => 0.1274,
        101 => 0.1301,
        102 => 0.1328,
        103 => 0.1356,
        104 => 0.1384,
        105 => 0.1412,
        106 => 0.1441,
        107 => 0.1470,
        108 => 0.1499,
        109 => 0.1529,
        110 => 0.1559,
        111 => 0.1589,
        112 => 0.1620,
        113 => 0.1651,
        114 => 0.1682,
        115 => 0.1714,
        116 => 0.1746,
        117 => 0.1778,
        118 => 0.1811,
        119 => 0.1844,
        120 => 0.1878,
        121 => 0.1912,
        122 => 0.1946,
        123 => 0.1980,
        124 => 0.2015,
        125 => 0.2050,
        126 => 0.2086,
        127 => 0.2122,
        128 => 0.2158,
        129 => 0.2195,
        130 => 0.2232,
        131 => 0.2269,
        132 => 0.2307,
        133 => 0.2345,
        134 => 0.2383,
        135 => 0.2422,
        136 => 0.2462,
        137 => 0.2501,
        138 => 0.2541,
        139 => 0.2581,
        140 => 0.2622,
        141 => 0.2663,
        142 => 0.2704,
        143 => 0.2746,
        144 => 0.2788,
        145 => 0.2831,
        146 => 0.2874,
        147 => 0.2917,
        148 => 0.2961,
        149 => 0.3005,
        150 => 0.3049,
        151 => 0.3094,
        152 => 0.3139,
        153 => 0.3185,
        154 => 0.3231,
        155 => 0.3277,
        156 => 0.3324,
        157 => 0.3371,
        158 => 0.3419,
        159 => 0.3467,
        160 => 0.3515,
        161 => 0.3564,
        162 => 0.3613,
        163 => 0.3662,
        164 => 0.3712,
        165 => 0.3762,
        166 => 0.3813,
        167 => 0.3864,
        168 => 0.3915,
        169 => 0.3967,
        170 => 0.4019,
        171 => 0.4072,
        172 => 0.4125,
        173 => 0.4178,
        174 => 0.4232,
        175 => 0.4286,
        176 => 0.4341,
        177 => 0.4396,
        178 => 0.4452,
        179 => 0.4507,
        180 => 0.4564,
        181 => 0.4620,
        182 => 0.4677,
        183 => 0.4735,
        184 => 0.4793,
        185 => 0.4851,
        186 => 0.4910,
        187 => 0.4969,
        188 => 0.5028,
        189 => 0.5088,
        190 => 0.5149,
        191 => 0.5209,
        192 => 0.5271,
        193 => 0.5332,
        194 => 0.5394,
        195 => 0.5457,
        196 => 0.5520,
        197 => 0.5583,
        198 => 0.5647,
        199 => 0.5711,
        200 => 0.5775,
        201 => 0.5840,
        202 => 0.5906,
        203 => 0.5972,
        204 => 0.6038,
        205 => 0.6104,
        206 => 0.6172,
        207 => 0.6239,
        208 => 0.6307,
        209 => 0.6375,
        210 => 0.6444,
        211 => 0.6514,
        212 => 0.6583,
        213 => 0.6653,
        214 => 0.6724,
        215 => 0.6795,
        216 => 0.6866,
        217 => 0.6938,
        218 => 0.7011,
        219 => 0.7083,
        220 => 0.7156,
        221 => 0.7230,
        222 => 0.7304,
        223 => 0.7379,
        224 => 0.7454,
        225 => 0.7529,
        226 => 0.7605,
        227 => 0.7681,
        228 => 0.7758,
        229 => 0.7835,
        230 => 0.7912,
        231 => 0.7991,
        232 => 0.8069,
        233 => 0.8148,
        234 => 0.8227,
        235 => 0.8307,
        236 => 0.8387,
        237 => 0.8468,
        238 => 0.8549,
        239 => 0.8631,
        240 => 0.8713,
        241 => 0.8796,
        242 => 0.8879,
        243 => 0.8962,
        244 => 0.9046,
        245 => 0.9130,
        246 => 0.9215,
        247 => 0.9301,
        248 => 0.9386,
        249 => 0.9473,
        250 => 0.9559,
        251 => 0.9646,
        252 => 0.9734,
        253 => 0.9822,
        254 => 0.9911,
        255 => 1.0000,
    ];

    /**
     * @var int
     */
    protected int $componentX;

    /**
     * @var int
     */
    protected int $componentY;

    /**
     * @var int
     */
    protected int $imageWidth;

    /**
     * @var int
     */
    protected int $imageHeight;

    /**
     * BlurHash constructor.
     *
     * @param  int  $componentX
     * @param  int  $componentY
     * @param  int  $resizedWidth
     */
    public function __construct(
        int $componentX = 4,
        int $componentY = 3,
        int $resizedWidth = 64
    ) {
        $this->setComponentX($componentX)
            ->setComponentY($componentY)
            ->setResizedImageMaxWidth($resizedWidth);
    }

    /**
     * Encode an image to blurhash string.
     *
     * @param  string|resource|Image|UploadedFile  $data
     * @return string
     */
    public function encode(mixed $data): string
    {
        $ac = $this->transform(
            $this->colors(
                $this->image($data)
            )
        );

        /** @var array<float> $dc */
        $dc = array_shift($ac);

        $hash = Base83::encode($this->componentX - 1 + ($this->componentY - 1) * 9, 1);

        $maximum = 1;

        if (!count($ac)) {
            $hash .= Base83::encode(0, 1);
        } else {
            $actual = max(array_map('max', $ac));

            $quantised = max(0, min(82, intval($actual * 166 - 0.5)));

            $maximum = ($quantised + 1) / 166;

            $hash .= Base83::encode($quantised, 1);
        }

        $hash .= Base83::encode(($this->toSRGB($dc[0]) << 16) + ($this->toSRGB($dc[1]) << 8) + $this->toSRGB($dc[2]), 4);

        foreach ($ac as $factor) {
            $hash .= Base83::encode(self::encodeAC($factor, $maximum), 2);
        }

        return $hash;
    }

    /**
     * Decode a blurhash string to an Intervention image.
     *
     * @param  string  $blurhash
     * @param  int  $width
     * @param  int  $height
     * @return Image
     */
    public function decode(string $blurhash, int $width, int $height): Image
    {
        $manager = new ImageManager(['driver' => 'gd']);

        $image = $manager->canvas($width, $height);

        $size = Base83::decode($blurhash[0]);

        $sizeX = ($size % 9) + 1;

        $sizeY = intval($size / 9) + 1;

        $colors = [$this->toRGB(Base83::decode(substr($blurhash, 2, 4)))];

        $maximum = (Base83::decode($blurhash[1]) + 1) / 166;

        for ($i = 1, $total = $sizeX * $sizeY; $i < $total; ++$i) {
            $value = Base83::decode(substr($blurhash, $i * 2 + 4, 2));

            $colors[$i] = $this->decodeAC($value, $maximum);
        }

        for ($y = 0; $y < $height; ++$y) {
            for ($x = 0; $x < $width; ++$x) {
                $r = $g = $b = 0;

                $piXWidth = M_PI * $x / $width;
                $piYHeight = M_PI * $y / $height;

                for ($j = 0; $j < $sizeY; ++$j) {
                    $cosHeight = cos($piYHeight * $j);

                    $sizeXJ = $j * $sizeX;

                    for ($i = 0; $i < $sizeX; ++$i) {
                        $color = $colors[$i + $sizeXJ];

                        $basis = cos($piXWidth * $i) * $cosHeight;

                        $r += $color[0] * $basis;
                        $g += $color[1] * $basis;
                        $b += $color[2] * $basis;
                    }
                }

                $image->pixel([$this->toSRGB($r), $this->toSRGB($g), $this->toSRGB($b)], $x, $y);
            }
        }

        return $image;
    }

    /**
     * Get resized image resource.
     *
     * @param  string|resource|Image|UploadedFile  $data
     * @return GdImage
     */
    protected function image(mixed $data): GdImage
    {
        $manager = new ImageManager(['driver' => 'gd']);

        $image = $manager->make($data);

        $image->resize(
            $this->imageWidth,
            null,
            function (Constraint $constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            }
        );

        $this->imageWidth = $image->width();

        $this->imageHeight = $image->height();

        $gd = $image->getCore();

        if (!($gd instanceof GdImage)) {
            throw new RuntimeException(
                'Something went wrong when getting GD resource.'
            );
        }

        return $gd;
    }

    /**
     * Get image colors for every pixel.
     *
     * @param  GdImage  $image
     * @return array<int, array<int, array<int, float>>>
     */
    protected function colors(GdImage $image): array
    {
        $colors = [];

        $cache = [];

        $isTrueColor = imageistruecolor($image);

        for ($x = 0; $x < $this->imageWidth; ++$x) {
            $colors[$x] = [];

            for ($y = 0; $y < $this->imageHeight; ++$y) {
                $rgb = imagecolorat($image, $x, $y);

                if ($rgb === false) {
                    throw new RuntimeException(
                        sprintf(
                            'Something went wrong when getting image color at x: %d, y: %d.',
                            $x,
                            $y,
                        )
                    );
                }

                if (!isset($cache[$rgb])) {
                    if ($isTrueColor) {
                        $red = ($rgb >> 16) & 0xFF;
                        $green = ($rgb >> 8) & 0xFF;
                        $blue = $rgb & 0xFF;
                    } else {
                        [
                            'red'   => $red,
                            'green' => $green,
                            'blue'  => $blue,
                        ] = imagecolorsforindex($image, $rgb);
                    }

                    $cache[$rgb] = [
                        self::$rgbToLinearMap[$red],
                        self::$rgbToLinearMap[$green],
                        self::$rgbToLinearMap[$blue],
                    ];
                }

                $colors[$x][$y] = $cache[$rgb];
            }
        }

        return $colors;
    }

    /**
     * Magic transform function.
     *
     * I don't know the meaning of the math calculation.
     *
     * @param  array<int, array<int, array<int, float>>>  $colors
     * @return array<int, array<int, float>>
     */
    protected function transform(array $colors): array
    {
        $factors = [];

        $scale = 1 / ($this->imageWidth * $this->imageHeight);

        for ($y = 0; $y < $this->componentY; ++$y) {
            $yHeight = M_PI * $y / $this->imageHeight;

            for ($x = 0; $x < $this->componentX; ++$x) {
                $normalisation = $x === 0 && $y === 0 ? 1 : 2;

                $xWidth = M_PI * $x / $this->imageWidth;

                $r = $g = $b = 0;

                for ($i = 0; $i < $this->imageWidth; ++$i) {
                    $cosWidth = $normalisation * cos($xWidth * $i);

                    for ($j = 0; $j < $this->imageHeight; ++$j) {
                        $basis = $cosWidth * cos($yHeight * $j);

                        $color = $colors[$i][$j];

                        $r += $basis * $color[0];
                        $g += $basis * $color[1];
                        $b += $basis * $color[2];
                    }
                }

                $factors[] = [
                    round($r * $scale, 7),
                    round($g * $scale, 7),
                    round($b * $scale, 7),
                ];
            }
        }

        return $factors;
    }

    /**
     * @param  int  $value
     * @return array<int, float>
     */
    protected function toRGB(int $value): array
    {
        $r = ($value >> 16) & 0xFF;
        $g = ($value >> 8) & 0xFF;
        $b = $value & 0xFF;

        return [
            self::$rgbToLinearMap[$r],
            self::$rgbToLinearMap[$g],
            self::$rgbToLinearMap[$b],
        ];
    }

    /**
     * @param  float  $value
     * @return int
     */
    protected function toSRGB(float $value): int
    {
        $value = max(0, min(1, $value));

        if ($value <= 0.0031308) {
            $value = $value * 12.92 * 255 + 0.5;
        } else {
            $value = (1.055 * pow($value, 1 / 2.4) - 0.055) * 255 + 0.5;
        }

        return intval($value);
    }

    /**
     * Encode ac factor.
     *
     * @param  array<int, float>  $color
     * @param  float  $max
     * @return int
     */
    protected function encodeAC(array $color, float $max): int
    {
        $r = $this->quantise($this->pow($color[0] / $max, 0.5));

        $g = $this->quantise($this->pow($color[1] / $max, 0.5));

        $b = $this->quantise($this->pow($color[2] / $max, 0.5));

        return $r * 19 * 19 + $g * 19 + $b;
    }

    /**
     * @param  int  $value
     * @param  float  $max
     * @return array<int, float>
     */
    protected function decodeAC(int $value, float $max): array
    {
        $r = intval($value / (19 * 19));

        $g = intval($value / 19) % 19;

        $b = $value % 19;

        return [
            $this->pow(($r - 9) / 9, 2) * $max,
            $this->pow(($g - 9) / 9, 2) * $max,
            $this->pow(($b - 9) / 9, 2) * $max,
        ];
    }

    /**
     * @param  float  $value
     * @return int
     */
    protected function quantise(float $value): int
    {
        return max(0, min(18, intval($value * 9 + 9.5)));
    }

    /**
     * @param  float  $value
     * @param  float  $exp
     * @return float
     */
    protected function pow(float $value, float $exp): float
    {
        return ($value < 0 ? -1 : 1) * pow(abs($value), $exp);
    }

    /**
     * Set component x.
     *
     * @param  int  $componentX
     * @return BlurHash
     */
    public function setComponentX(int $componentX): self
    {
        $this->componentX = $this->normalizeComponent($componentX);

        return $this;
    }

    /**
     * Set component y.
     *
     * @param  int  $componentY
     * @return BlurHash
     */
    public function setComponentY(int $componentY): self
    {
        $this->componentY = $this->normalizeComponent($componentY);

        return $this;
    }

    /**
     * Restrict component value between 1 and 9.
     *
     * @param  int  $value
     * @return int
     */
    protected function normalizeComponent(int $value): int
    {
        return max(1, min(9, $value));
    }

    /**
     * Set resized image max width.
     *
     * @param  int  $imageWidth
     * @return BlurHash
     */
    public function setResizedImageMaxWidth(int $imageWidth): self
    {
        $this->imageWidth = $imageWidth;

        return $this;
    }
}
