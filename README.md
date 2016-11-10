Simple bitmask implementation
===

[![License](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Build Status](https://travis-ci.org/Aliance/Bitmask.svg?branch=master)](https://travis-ci.org/Aliance/Bitmask)

Installation
---

For install library you need to modify your composer configuration file

```
    "aliance/bitmask": "*"
```

And just run installation command

```
    $ composer.phar install
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

For completely tests running just call `phpunit` command from `./vendor/bin`

```
Aliance/Bitmask $ ./vendor/bin/phpunit 
PHPUnit 4.8.27 by Sebastian Bergmann and contributors.

........

Time: 85 ms, Memory: 4.00MB

OK (8 tests, 92 assertions)
```
