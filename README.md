# BlurHash

[![Testing](https://github.com/bepsvpt/blurhash/actions/workflows/testing.yml/badge.svg)](https://github.com/bepsvpt/blurhash/actions/workflows/testing.yml)
[![Latest Stable Version](https://poser.pugx.org/bepsvpt/blurhash/v/stable)](https://packagist.org/packages/bepsvpt/blurhash)
[![Total Downloads](https://poser.pugx.org/bepsvpt/blurhash/downloads)](https://packagist.org/packages/bepsvpt/blurhash)
[![License](https://poser.pugx.org/bepsvpt/blurhash/license)](https://packagist.org/packages/bepsvpt/blurhash)

A PHP implementation of [BlurHash](https://github.com/woltapp/blurhash) with Laravel integration.

BlurHash is a compact representation of a placeholder for an image.

![screenshot](https://raw.githubusercontent.com/bepsvpt/blurhash/main/screenshot.png)

<p align="center">Nr8%YLkDR4j[aej]NSaznzjuk9ayR3jYofayj[f6</p>

- [Version](#version)
- [Installation](#installation)
- [Usage](#usage)
- [Changelog](#changelog)
- [Upgrade](#upgrade)
- [License](#license)

## Version

3.0.1

### Supported Laravel Version

8.0 ~ 11.x

## Installation

Install using composer

```shell
composer require bepsvpt/blurhash
```

Publish config file

```shell
php artisan vendor:publish --provider="Bepsvpt\Blurhash\BlurHashServiceProvider"
```

Set up config file on config/blurhash.php

Done!

## Usage

### Facade

```php
BlurHash::encode($path);
```

`$file` can be `UploadedFile` or a file path string.

### app helper function

```php
app('blurhash')
  ->setComponentX(7)
  ->setComponentY(4)
  ->setMaxSize(96)
  ->encode(request('file'));
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for details.

## Upgrade

Please see [UPGRADE](UPGRADE.md) for details.

## License

BlurHash is licensed under [The MIT License (MIT).](LICENSE.md)
