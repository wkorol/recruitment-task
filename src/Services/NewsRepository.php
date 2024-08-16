<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\Database;
use App\Dto\News;
use PDO;
use PDOException;

class NewsRepository
{
    private PDO $pdo;
    public function __construct(private readonly Database $database)
    {
        $this->pdo = $this->database->connection();
    }

    public function getNewsById(int $id): ?News
    {
        $query = 'SELECT * FROM news WHERE id = :id';

        try {
            $statement = $this->pdo->prepare($query);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return new News(
                    id: $result['id'],
                    title: $result['title'],
                    description: $result['description']
                );
            } else {
                return null;
            }
        } catch (PDOException $e) {
            throw new PDOException('Error retrieving news: ' . $e->getMessage());
        }
    }

    /**
     * @return News[]
     */
    public function getAllNews(): array
    {
        $query = 'SELECT * FROM news';
        $newsList = [];

        try {
            $statement = $this->pdo->query($query);
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results as $result) {
                $newsList[] = new News(
                    id: $result['id'],
                    title: $result['title'],
                    description: $result['description']
                );
            }

            return $newsList;
        } catch (PDOException) {
            throw new PDOException('Error retrieving all news.');
        }
    }

    public function deleteNewsById(int $id): bool
    {
        if ($this->getNewsById($id)) {
            $query = 'DELETE FROM news WHERE id = :id';

            try {
                $statement = $this->pdo->prepare($query);
                $statement->bindParam(':id', $id, PDO::PARAM_INT);

                return $statement->execute();
            } catch (PDOException) {
                throw new PDOException('Error deleting news.');
            }
        }
        return false;
    }

    public function createNews(string $title, string $description): int
    {
        $query = 'INSERT INTO news (title, description) VALUES (:title, :description)';

        try {
            $statement = $this->pdo->prepare($query);
            $statement->bindParam(':title', $title);
            $statement->bindParam(':description', $description);

            $statement->execute();


            return (int)$this->pdo->lastInsertId();

        } catch (PDOException) {
            throw new PDOException('Error creating news.');
        }
    }

    public function updateNews(int $id, string $title, string $description): int
    {
        if ($this->getNewsById($id)) {
            $query = 'UPDATE news SET title = :title, description = :description WHERE id = :id';

            try {
                $statement = $this->pdo->prepare($query);
                $statement->bindParam(':title', $title);
                $statement->bindParam(':description', $description);
                $statement->bindParam(':id', $id, PDO::PARAM_INT);

                $statement->execute();

                return (int)$id;
            } catch (PDOException $e) {
                throw new PDOException('Error updating news.' . $e->getMessage());
            }
        }
        return 0;
    }

}