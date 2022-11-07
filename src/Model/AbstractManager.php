<?php

namespace App\Model;

/**
 * Abstract class handling default manager.
 */
abstract class AbstractManager
{
    public const TABLE = '';
    protected \PDO $pdo;

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
    public function selectOneById(int $id): array|false
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

     public function active($correct_page)
     {
         $url_array = explode('/', $_SERVER['REQUEST_URI']);
         $url = end($url_array);
         if ($correct_page == $url) {
             echo 'active'; // class name in css
         }

         return $this->em->getRepository('CoursatBundle:test')->find($correct_page);
     }

     public function likeAnime($id)
     {
         if ('POST' === $_SERVER['REQUEST_METHOD']) {
             $cookie = new Cookie();
             $cookie->setCookie('anime_like', $id);

             return header('Location: /anime');
         }
     }
}
