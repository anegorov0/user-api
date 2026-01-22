<?php
namespace App\Repository;
use App\Model\User;

class JsonUserRepository implements UserRepositoryInterface {
    private string $filePath;
    public function __construct(string $filePath){
        $this->filePath = $filePath;
    }


    /**
     * @throws \JsonException
     */
    public function getAll(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $json = file_get_contents($this->filePath);
        if ($json === '' || $json === false) {
            return [];
        }
        $data = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

        if (!is_array($data)) {
            return [];
        }

        $users = [];
        foreach ($data as $item) {
            $users[] = new User(
                $item['id'],
                $item['name'],
                $item['familiya'],
                $item['email'],
            );
        }
        return $users;
    }

    /**
     * @throws \JsonException
     */
    public function saveAll(array $users): void
    {
        $data = [];

        foreach ($users as $user) {
            $data[] = $user->toArray();
        }
        file_put_contents($this->filePath, json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
    }

    /**
     * @throws \JsonException
     */
    public function nextId(): int
    {
        $users = $this->getAll();
        if (empty($users)) {
            return 1;
        }
        $ids = array_map(static fn (User $user) => $user->getId(), $users);
        return max($ids)+1;
    }
}