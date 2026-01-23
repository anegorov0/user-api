<?php

namespace App\Repository;

use App\Model\User;
use PDO;
use Throwable;

class MysqlUserRepository implements UserRepositoryInterface
{
    private PDO $pdo;

    public function __construct()
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $_ENV['DB_HOST'],
            $_ENV['DB_PORT'],
            $_ENV['DB_NAME']
        );

        $this->pdo = new PDO(
            $dsn,
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]
        );
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query(
            'SELECT id, name, familiya, email FROM users ORDER BY id'
        );

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($rows as $row) {
            $users[] = new User(
                (int)$row['id'],
                $row['name'],
                $row['familiya'],
                $row['email']
            );
        }

        return $users;
    }

    /**
     * @throws Throwable
     */
    public function saveAll(array $users): void
    {
        $this->pdo->beginTransaction();

        try {
            $this->pdo->exec('DELETE FROM users');

            $stmt = $this->pdo->prepare(
                'INSERT INTO users (id, name, familiya, email)
                 VALUES (:id, :name, :familiya, :email)'
            );

            foreach ($users as $user) {
                $stmt->execute([
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'familiya' => $user->getFamiliya(),
                    'email' => $user->getEmail(),
                ]);
            }

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function nextId(): int
    {
        $stmt = $this->pdo->query('SELECT MAX(id) FROM users');
        $maxId = $stmt->fetchColumn();

        return $maxId ? ((int)$maxId + 1) : 1;
    }
}
