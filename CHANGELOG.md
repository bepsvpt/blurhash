# BlurHash Changelog

## 4.x

- 4.0.1 (2025-07-02)
  - Fix vips driver encoding issue for single band image ([#15](https://github.com/bepsvpt/blurhash/issues/15))

- 4.0.0 (2025-03-16)
  - Support Laravel 12
  - Drop Lumen

## 3.x

- 3.0.1 (2024-03-27)
  - Fix path when encoding from UploadedFile

- 3.0.0 (2024-02-10)
  - Added support for the libvips ([php-vips](https://github.com/libvips/php-vips)) image processing library as a driver.
  - Separated from the [intervention/image](https://github.com/Intervention/image) library.
  - Support Laravel 11

## 2.x

- 2.1.1 (2023-04-24)
  - Use `intdiv()` instead of float div + int cast ([#6](https://github.com/bepsvpt/blurhash/pull/6))

- 2.1.0 (2023-02-07)
  - Support Laravel 10

- 2.0.0 (2022-07-02)
  - Support decode from blurhash string
  - Drop Laravel 6 and 7
  - Drop PHP 7.2, 7.3, and 7.4

## 1.x

- 1.0.1 (2022-03-09)
  - Support Laravel 9

- 1.0.0 (2020-12-12)
  - Support PHP 8.0
