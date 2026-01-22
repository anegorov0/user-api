<?php
namespace App\Command;

use App\Service\UserService;
use App\Command\CommandInterface;

class UserDeleteCommand implements CommandInterface{
    private UserService $userService;
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }
    public function execute(array $args): void{
        $id = $args[2] ?? null;

        if (!$id || !is_numeric($id)) {
            echo "Usai: php app.php user:delete <id>\n";
            return;
        }
        $this->userService->deleteUserById((int)$id);
        echo "User s etim id {id} ydalen\n";
    }
}