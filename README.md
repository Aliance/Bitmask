Simple bitmask implementation
===

[![License](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)

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
