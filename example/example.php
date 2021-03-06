<?php

declare(strict_types=1);

require_once realpath(__DIR__ . '/../vendor/autoload.php');

/*
 * For example, we have some blog with users stored in some storage.
 * The main entity for blog – news.
 * Users should have the ACL for creating, reading, updating and deleting news.
 */

define('ACCESS_CREATE', 0);
define('ACCESS_READ', 1);
define('ACCESS_UPDATE', 2);
define('ACCESS_DELETE', 3);
// etc, up to 63

// some users from storage
$user = [
    'access_level' => 0, // default access level – restrict access to all
];

// create a Bitmask object, passing user bitmask from storage
$Bitmask = new \Aliance\Bitmask\Bitmask($user['access_level']);

checkRights($Bitmask);

// allow user to read
$Bitmask->setBit(ACCESS_READ);

checkRights($Bitmask);

function checkRights(\Aliance\Bitmask\Bitmask $Bitmask)
{
    echo 'Check user for all access levels:', PHP_EOL;
    echo 'Create: ', $Bitmask->issetBit(ACCESS_CREATE) ? 'yes' : 'no', PHP_EOL;
    echo 'Read: ', $Bitmask->issetBit(ACCESS_READ) ? 'yes' : 'no', PHP_EOL;
    echo 'Update: ', $Bitmask->issetBit(ACCESS_UPDATE) ? 'yes' : 'no', PHP_EOL;
    echo 'Delete: ', $Bitmask->issetBit(ACCESS_DELETE) ? 'yes' : 'no', PHP_EOL;
    echo str_repeat('–', 35), PHP_EOL, PHP_EOL;
}
