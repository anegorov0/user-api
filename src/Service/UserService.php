<?php
namespace App\Service;

use App\Repository\UserRepositoryInterface;
use App\Model\User;

class UserService{
    private UserRepositoryInterface $userRepository;
    public function __construct(UserRepositoryInterface $userRepository){
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(): array{
        return $this->userRepository->getAll();
    }

    public function addUser(User $user): void{
        $users = $this->userRepository->getAll();
        $users[] = $user;
        $this->userRepository->saveAll($users);
    }

    public function deleteUserById(int $id): void{
        $users = $this->userRepository->getAll();
        $users = array_filter($users, static fn (User $user) => $user->getId() !== $id);
        $this->userRepository->saveAll(array_values($users));
    }

    public function nextId(): int{
        return $this->userRepository->nextId();
    }

    public function getNextId(): int{
        return $this->userRepository->nextId();
    }
}