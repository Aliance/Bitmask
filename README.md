Simple bitmask implementation
===

[![License](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Packagist](https://img.shields.io/packagist/v/aliance/bitmask.svg)](https://packagist.org/packages/aliance/bitmask)
![PHP Version](https://img.shields.io/badge/PHP-7.4-green.svg)
[![Code Coverage](https://scrutinizer-ci.com/g/Aliance/Bitmask/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Aliance/Bitmask/?branch=master)

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
docker run --rm -it --volume $PWD:/app -u $(id -u):$(id -g) composer:1.10.19 i
```

Usage
---

See usage in [sample](./example/example.php) file.

```bash
docker run -it --rm -v "$PWD":/usr/src/bitmask -w /usr/src/bitmask php:7.4-cli php example/example.php  
```
```
Check user for all access levels:
Create: no
Read: no
Update: no
Delete: no
–––––––––––––––––––––––––––––––––––

Check user for all access levels:
Create: no
Read: yes
Update: no
Delete: no
–––––––––––––––––––––––––––––––––––

```

Tests
---

For completely tests running just call `composer exec phpunit` or use
```bash
docker run -it --rm -v "$PWD":/usr/src/bitmask -w /usr/src/bitmask php:7.4-cli php ./vendor/bin/phpunit 
```

License
---

This software is distributed under [MIT license](LICENSE).
