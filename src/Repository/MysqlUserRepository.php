<?php
namespace App\Repository;

use App\Model\User;

class MysqlUserRepository implements UserRepositoryInterface {
    public function getAll(): array{
        throw new \RuntimeException('Mysql repo not implemented');
    }

    public function saveAll(array $users): void{
        throw new \RuntimeException('Mysql repo not implemented');
    }

    public function nextId(): int{
        throw new \RuntimeException('Mysql repo not implemented');
    }

}