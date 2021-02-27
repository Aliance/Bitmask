Simple bitmask implementation
===

[![License](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Packagist](https://img.shields.io/packagist/v/aliance/bitmask.svg)](https://packagist.org/packages/aliance/bitmask)
[![Build Status](https://travis-ci.org/Aliance/Bitmask.svg?branch=master)](https://travis-ci.org/Aliance/Bitmask)
[![Code Coverage](https://scrutinizer-ci.com/g/Aliance/Bitmask/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Aliance/Bitmask/?branch=master)

About
---

Bitmask is a simple PHP implementation of bitwise operations for creating masks.
Can be used for some flags implementation.
Currently supported PHP version: >= 7.4

Installation
---

Install the latest version with composer:

```bash
$ composer require aliance/bitmask
```

Usage
---

See usage in [sample](./example/example.php) file.

```
Aliance/Bitmask $ php -f example/example.php 
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

For completely tests running just call `composer exec phpunit`.

License
---

This software is distributed under [MIT license](LICENSE).
