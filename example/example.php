<?php

declare(strict_types=1);

use Aliance\Bitmask\Bitmask;

require __DIR__ . '/../vendor/autoload.php';

enum Permission: int
{
    case Create = 0;
    case Read = 1;
    case Update = 2;
    case Delete = 3;
}

// A user record loaded from storage already has the Create permission.
$user = ['access_level' => 1];
$rights = new Bitmask($user['access_level']);
$rights->setBit(Permission::Read->value);
$user['access_level'] = $rights->getMask();

echo 'Stored mask: ', $user['access_level'], PHP_EOL;
$savedRights = new Bitmask($user['access_level']);
foreach (Permission::cases() as $permission) {
    echo $permission->name, ': ', $savedRights->issetBit($permission->value) ? 'yes' : 'no', PHP_EOL;
}
