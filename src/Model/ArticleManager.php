<?php

namespace App\Model;

class ArticleManager extends AbstractManager
{
    public const TABLE = 'articles';

    /**
     * Insert new article in database.
     */
    public function insertArticle(array $article): int
    {
        $statement = $this->pdo->prepare('INSERT INTO '.self::TABLE.' (title, content, author, picture, date, is_featured) VALUES (:title, :content, :author, :picture, now(), :featured)');
        $statement->bindValue(':title', $article['article-title'], \PDO::PARAM_STR);
        $statement->bindValue(':content', $article['article-content'], \PDO::PARAM_STR);
        $statement->bindValue(':picture', $article['article-picture'], \PDO::PARAM_STR);
        $statement->bindValue(':author', $article['article-author'], \PDO::PARAM_STR);

        $statement->bindValue(':featured', $article['article-featured'], \PDO::PARAM_STR);

        $statement->execute();

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Update article in database.
     */
    public function editArticle(array $item): bool
    {
        $statement = $this->pdo->prepare('UPDATE '.self::TABLE.' SET title = :title, content = :content, picture = :picture, author = :author, date = now() WHERE id=:id');
        $statement->bindValue('id', $item['id'], \PDO::PARAM_INT);
        $statement->bindValue('title', $item['article-title'], \PDO::PARAM_STR);
        $statement->bindValue('content', $item['article-content'], \PDO::PARAM_STR);
        $statement->bindValue('picture', $item['article-picture'], \PDO::PARAM_STR);
        $statement->bindValue('author', $item['article-author'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}
