<?php
namespace App\Command;

use App\Command\CommandInterface;
use App\Service\UserService;

class UserListCommand implements CommandInterface{
    private UserService $userService;
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }
    public function execute(array $args): void{
        $users = $this->userService->getAllUsers();

        if (empty($users)) {
            echo "Ne naideni users\n";
            return;
        }
        foreach ($users as $user) {
            echo sprintf(
                "%d | %s %s | %s\n",
                $user->getId(),
                $user->getName(),
                $user->getFamiliya(),
                $user->getEmail()
            );
        }
    }
}