<?php

use App\Command\UserDeleteCommand;
use App\Command\UserListCommand;
use App\Model\User;
use App\Repository\JsonUserRepository;
use App\Service\UserService;
use App\Command\UserAddCommand;

require __DIR__ . '/vendor/autoload.php';


$commandName = $argv[1] ?? null;

$repository = new JsonUserRepository(__DIR__ . '/db/users.json');
$userService = new UserService($repository);

$command = [
    'user:list' => new UserListCommand($userService),
    'user:add' => new UserAddCommand($userService),
    'user:delete' => new UserDeleteCommand($userService),
];

if (!$commandName || !isset($command[$commandName])) {
    echo "Xz chto za command bratik\n";
    exit;
}

$command[$commandName]->execute($argv);