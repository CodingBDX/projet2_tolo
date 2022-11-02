<?php

namespace App\Model;

use PDO;

class ArticleManager extends AbstractManager
{
    public const TABLE = 'articles';

    /**
     * Insert new item in database.
     */
    public function insert(array $article): int
    {
        $statement = $this->pdo->prepare('INSERT INTO '.self::TABLE.' (`title`,`content`,`picture`) VALUES (:title,:content,:picture)');
        $statement->bindValue('title', $article['title'], PDO::PARAM_STR);

        $statement->bindValue('content', $article['content'], PDO::PARAM_STR);

        $statement->bindValue('picture', $article['picture'], PDO::PARAM_STR);

        $statement->execute();

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Update item in database.
     */
    public function update(array $item): bool
    {
        $statement = $this->pdo->prepare('UPDATE '.self::TABLE.' SET `title` = :title WHERE id=:id');
        $statement->bindValue('id', $item['id'], PDO::PARAM_INT);
        $statement->bindValue('title', $item['title'], PDO::PARAM_STR);

        return $statement->execute();
    }

    public function selectAll(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM '.static::TABLE;
        if ($orderBy) {
            $query .= ' ORDER BY '.$orderBy.' '.$direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }
}
