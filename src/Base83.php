<?php

namespace Bepsvpt\Blurhash;

class Base83
{
    /**
     * Base 63 encoder/decoder character set.
     *
     * @var array<int, string>
     */
    public static array $characters = [
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D',
        'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
        'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f',
        'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
        'u', 'v', 'w', 'x', 'y', 'z', '#', '$', '%', '*', '+', ',', '-', '.',
        ':', ';', '=', '?', '@', '[', ']', '^', '_', '{', '|', '}', '~',
    ];

    /**
     * Base 63 encoder/decoder character index map.
     *
     * @var array<int|string, int>
     */
    protected static array $indexMap = [
        '0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4,
        '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9,
        'A' => 10, 'B' => 11, 'C' => 12, 'D' => 13, 'E' => 14, 'F' => 15,
        'G' => 16, 'H' => 17, 'I' => 18, 'J' => 19, 'K' => 20, 'L' => 21,
        'M' => 22, 'N' => 23, 'O' => 24, 'P' => 25, 'Q' => 26, 'R' => 27,
        'S' => 28, 'T' => 29, 'U' => 30, 'V' => 31, 'W' => 32, 'X' => 33,
        'Y' => 34, 'Z' => 35, 'a' => 36, 'b' => 37, 'c' => 38, 'd' => 39,
        'e' => 40, 'f' => 41, 'g' => 42, 'h' => 43, 'i' => 44, 'j' => 45,
        'k' => 46, 'l' => 47, 'm' => 48, 'n' => 49, 'o' => 50, 'p' => 51,
        'q' => 52, 'r' => 53, 's' => 54, 't' => 55, 'u' => 56, 'v' => 57,
        'w' => 58, 'x' => 59, 'y' => 60, 'z' => 61, '#' => 62, '$' => 63,
        '%' => 64, '*' => 65, '+' => 66, ',' => 67, '-' => 68, '.' => 69,
        ':' => 70, ';' => 71, '=' => 72, '?' => 73, '@' => 74, '[' => 75,
        ']' => 76, '^' => 77, '_' => 78, '{' => 79, '|' => 80, '}' => 81,
        '~' => 82,
    ];

    /**
     * Encode an integer to string.
     *
     * @param  int  $value
     * @param  int  $length
     * @return string
     */
    public static function encode(int $value, int $length): string
    {
        static $powOf83 = [1, 83, 6889, 571787, 47458321];

        $result = '';

        for ($i = 1; $i <= $length; ++$i) {
            $digit = intval($value / $powOf83[$length - $i]) % 83;

            $result .= self::$characters[$digit];
        }

        return $result;
    }

    /**
     * Decode a string to integer.
     *
     * @param  string  $encoded
     * @return int
     */
    public static function decode(string $encoded): int
    {
        $result = 0;

        foreach (str_split($encoded) as $char) {
            $result = $result * 83 + static::$indexMap[$char];
        }

        return $result;
    }
}
