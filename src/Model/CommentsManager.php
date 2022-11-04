<?php

namespace App\Model;

class CommentsManager extends AbstractManager
{
    public const TABLE = 'comments';

    public function getUserComment()
    {
        $statement = $this->pdo->prepare('SELECT * FROM '.self::TABLE.' INNER JOIN users u ON comments.users_id = u.id ');

        $statement->execute();

        return $statement->fetch();
    }

    public function addComment($userComment): int
    {
        $statement = $this->pdo->prepare('INSERT INTO comments (content, date) VALUES (:userComment, now())');
        $statement->bindValue(':userComment', $userComment, \PDO::PARAM_STR);
        $statement->execute();

        return (int) $this->pdo->lastInsertId();
    }
}
