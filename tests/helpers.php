<?php

use Composer\Semver\Comparator;

/**
 * Check the current installed GD
 * library is before 2.2.0 or not.
 *
 * @return bool
 */
function isOldGdLibrary(): bool
{
    $match = preg_match(
        '/\d+\.\d+\.\d+/',
        gd_info()['GD Version'],
        $matches
    );

    if (!$match) {
        return false;
    }

    return Comparator::greaterThan('2.2.0', $matches[0]);
}
