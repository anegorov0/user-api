<?php

namespace App\Model;

class user
{
    private $id;
    private $name;
    private $familiya;
    private $email;

    public function __construct(int $id, string $name, string $familiya, string $email){
        $this->id = $id;
        $this->name = $name;
        $this->familiya = $familiya;
        $this->email = $email;
    }

    public function getId(): ?int{
        return $this->id;
    }

    public function getName(): ?string{
        return $this->name;
    }

    public function getFamiliya(): ?string{
        return $this->familiya;
    }

    public function getEmail(): ?string{
        return $this->email;
    }

    public function toArray(): array{
        return [
            'id' => $this->id,
            'name' => $this->name,
            'familiya' => $this->familiya,
            'email' => $this->email
        ];
    }
}