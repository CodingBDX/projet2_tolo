<?php

namespace App\Model;

use PDO;

/**
 * Abstract class handling default manager.
 */
abstract class AbstractManager
{
    public const TABLE = '';
    protected PDO $pdo;

    public function __construct()
    {
        $connection = new Connection();
        $this->pdo = $connection->getConnection();
    }

    /**
     * Get all row from database.
     */
    public function selectAll(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM '.static::TABLE;
        if ($orderBy) {
            $query .= ' ORDER BY '.$orderBy.' '.$direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }

    /**
     * Get one row from database by ID.
     */
    public function selectOneById(string $id): array|false
    {
        // prepared request
        $statement = $this->pdo->prepare('SELECT * FROM '.static::TABLE.' WHERE id=:id');
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    /**
     * Delete row form an ID.
     */
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare('DELETE FROM '.static::TABLE.' WHERE id=:id');
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
