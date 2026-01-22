<?php
namespace App\Command;

use App\Service\UserService;
use App\Command\CommandInterface;
use App\Model\User;

class UserAddCommand implements CommandInterface{

    private UserService $userService;
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }
    public function execute(array $args): void
    {
        $name = $args[2] ?? null;
        $familiya = $args[3] ?? null;
        $email = $args[4] ?? null;

        if (!$name || !$familiya || !$email) {
            echo "Uzai: php app.php user:add <name> <familiya> <email>\n";
            return;
        }

        // TODO: Implement execute() method.

        $id = $this->userService->nextId();

        $user = new User(
            $id,
            $name,
            $familiya,
            $email
        );
        $this->userService->addUser($user);
        echo "Dobavlen user c etim id {$id}\n";
    }

}