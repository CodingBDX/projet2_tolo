<?php

namespace App\Model;

use PDO;

/**
 * Abstract class handling default manager.
 */
class UserManager extends AbstractManager
{
    public const TABLE = 'users';
    protected \PDO $pdo;

    public function __construct()
    {
        $connection = new Connection();
        $this->pdo = $connection->getConnection();
    }

     /**
      * Get all row from database.
      */

    // register user
     public function insert(array $user): int
     {
         $hash_password = password_hash($user['password'], PASSWORD_DEFAULT);

         $statement = $this->pdo->prepare('INSERT INTO '.self::TABLE.' (`name`, `password`, `mail`) VALUES (:name,:password,:mail)');
         $statement->bindValue('name', $user['name'], \PDO::PARAM_STR);

         $statement->bindValue('password', $hash_password, \PDO::PARAM_STR);

         $statement->bindValue('mail', $user['mail'], \PDO::PARAM_STR);

         $statement->execute();

         return $this->pdo->lastInsertId();
     }

         public function update(array $user): bool
         {
             $statement = $this->pdo->prepare('UPDATE '.self::TABLE.' SET `name` = :name, `password` = :password, `mail` = :mail WHERE id=:id');
             $statement->bindValue('id', $user['id'], \PDO::PARAM_INT);
             $statement->bindValue('name', $user['name'], \PDO::PARAM_STR);

             $statement->bindValue('password', $user['password'], \PDO::PARAM_STR);
             $statement->bindValue('mail', $user['mail'], \PDO::PARAM_STR);

             return $statement->execute();
         }

          public function isLogin(string $mail, string $password): ?array
          {
              $statement = $this->pdo->prepare('SELECT * FROM '.self::TABLE.' WHERE mail=:mail AND password=:password');

              $statement->bindValue('mail', $mail, \PDO::PARAM_STR);

              $statement->bindValue('password', $password, \PDO::PARAM_STR);

              $statement->execute();

              return $statement->fetchAll(\PDO::FETCH_ASSOC);
              //   return (int) $this->pdo->lastInsertId();
          }

          public function decrypt_hash($password, $hash)
          {
              if (password_verify($password, $hash)) {
                  echo 'Password is valid!';
              } else {
                  echo 'Invalid password.';
              }
          }
}
