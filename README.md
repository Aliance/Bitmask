Simple bitmask implementation
===

[![License](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Packagist](https://img.shields.io/packagist/v/aliance/bitmask.svg)](https://packagist.org/packages/aliance/bitmask)
![PHP Version](https://img.shields.io/badge/PHP-8.3-green.svg)
[![Code Coverage](https://codecov.io/gh/Aliance/Bitmask/branch/master/graph/badge.svg)](https://codecov.io/gh/Aliance/Bitmask)

About
---

Bitmask is a simple PHP implementation of bitwise operations for creating masks.
Can be used for some flags' implementation.
Supports only 64 bits (from 0 to 63) on x64 platforms.

Installation
---

Install the latest version with composer:

```bash
composer require aliance/bitmask
```

If you checkout this library for testing purposes, install its dependencies:

```bash
docker run --rm -it --volume $PWD:/app -u $(id -u):$(id -g) composer:2 i
```

Usage
---

Pass a bit position (0 through 63) to `setBit()`, `unsetBit()` and `issetBit()`.
Pass an integer mask to `addMask()` and `deleteMask()`. For example, starting from an empty mask:

```php
use Aliance\Bitmask\Bitmask;

(new Bitmask())->setBit(3)->getMask();  // 8 (bit 3)
(new Bitmask())->addMask(3)->getMask(); // 3 (bits 0 and 1)
```

The [permission example](./example/example.php) uses an integer-backed enum to name bit positions.
It loads a user with Create permission, grants Read, and saves the updated value with `getMask()`.
Loading that value restores both permissions.

PHP uses signed integers. Setting bit 63 makes the mask negative.
Use a signed 64-bit column such as `BIGINT` to store the full range and pass the signed value back to the constructor when reading it.

```bash
docker run --rm -v "$PWD":/usr/src/bitmask -w /usr/src/bitmask php:8.3-cli php example/example.php
```

```text
Stored mask: 3
Create: yes
Read: yes
Update: no
Delete: no
```

Tests
---

For completely tests running just call `composer exec phpunit` or use
```bash
docker run -it --rm -v "$PWD":/usr/src/bitmask -w /usr/src/bitmask php:8.3-cli php ./vendor/bin/phpunit 
```

License
---

This software is distributed under [MIT license](LICENSE).
