<?php

namespace App\Repository;

use App\Model\User;

interface UserRepositoryInterface{

    public function getAll(): array;
    public function saveAll(array $user): void;
    public function nextId(): int;

}