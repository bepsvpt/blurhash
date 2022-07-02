<?php

return [

    /*
     * Components picked from image x and y axis.
     *
     * The more components you pick, the more information
     * is retained in the placeholder, but the longer the
     * BlurHash string will be. Also, it doesn't always
     * look good with too many components.
     *
     * Valid value is from 1 to 9.
     */

    'components-x' => 4,

    'components-y' => 3,

    /*
     * Resize image max width.
     *
     * When encoding the image, image will resize to
     * small one to optimize performance. It is not
     * recommend to set the value larger than 256.
     *
     */

    'resized-image-max-width' => 64,

];
