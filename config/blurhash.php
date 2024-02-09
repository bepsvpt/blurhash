<?php

return [

    /*
     * The image driver is used for encoding and decoding blurhash.
     *
     * Supported drivers include "gd", "imagick", and "php-vips".
     */

    'driver' => 'gd',

    /*
     * Components are selected from the image's x and y axis.
     *
     * Choosing more components means the placeholder will
     * retain more information, but it also makes the
     * BlurHash string longer. However, having too many
     * components doesn't always result in a better look.
     *
     * The valid range for values is from 1 to 9.
     */

    'components-x' => 4,

    'components-y' => 3,

    /*
     * When encoding the image, it will resize to a smaller
     * version to enhance performance. It's not recommended
     * to set the max width and height values larger than 256.
     */

    'resized-max-size' => 64,

];
