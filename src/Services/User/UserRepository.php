<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Dto\User;
use PDO;
use PDOException;
use src\Config\Database;

class UserRepository implements UserRepositoryInterface
{
    private PDO $pdo;
    public function __construct(private readonly Database $database)
    {
        $this->pdo = $this->database->connection();
    }

    public function getUserByLogin(?string $login): ?User
    {
        $query = 'SELECT id, login, password FROM "user" WHERE login = :login';

        try {
            $statement = $this->pdo->prepare($query);
            $statement->bindParam(':login', $login);
            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return new User(
                    id: $result['id'],
                    login: $result['login'],
                    password: $result['password']
                );
            }
            return null;
        } catch (PDOException) {
            throw new PDOException('Error retrieving user.');
        }
    }
}