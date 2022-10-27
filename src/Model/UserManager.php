<?php

namespace App\Model;

use PDO;

/**
 * Abstract class handling default manager.
 */
class UserManager extends AbstractManager
{
    public const TABLE = 'users';
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
}
