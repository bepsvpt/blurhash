# BlurHash

[![Testing](https://github.com/bepsvpt/blurhash/actions/workflows/testing.yml/badge.svg)](https://github.com/bepsvpt/blurhash/actions/workflows/testing.yml)
[![Latest Stable Version](https://poser.pugx.org/bepsvpt/blurhash/v/stable)](https://packagist.org/packages/bepsvpt/blurhash)
[![Total Downloads](https://poser.pugx.org/bepsvpt/blurhash/downloads)](https://packagist.org/packages/bepsvpt/blurhash)
[![License](https://poser.pugx.org/bepsvpt/blurhash/license)](https://packagist.org/packages/bepsvpt/blurhash)

A PHP implementation of [BlurHash](https://github.com/woltapp/blurhash) with Laravel integration.

BlurHash is a compact representation of a placeholder for an image.

![screenshot](screenshot.png)

<p align="center">Nr8%YLkDR4j[aej]NSaznzjuk9ayR3jYofayj[f6</p>

## Version

2.0.0

## Supported Laravel Version

8.0 ~ 9.x

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
BlurHash::encode($file);
```

`$file` can be any [Intervention make method](https://image.intervention.io/v2/api/make) acceptable source.

### app helper function

```php
app('blurhash')
  ->setComponentX(7)
  ->setComponentY(4)
  ->setResizedImageMaxWidth(96)
  ->encode(request('file'));
```

## License

BlurHash is licensed under [The MIT License (MIT).](LICENSE.md)
