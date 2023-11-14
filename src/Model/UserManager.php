<?php

namespace App\Model;

use PDO;

class UserManager extends AbstractManager
{
    public const TABLE = 'user';

    public function selectOneByEmail(string $email)
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . static::TABLE . " WHERE email=:email");
        $statement->bindValue('email', $email, PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }

    public function insert(array $user): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . static::TABLE . "
            (`email`, `password`, `pseudo`, `firstname`, `lastname`)
            VALUES (:email, :password, :pseudo, :firstname, :lastname)
        ");
        $statement->bindValue(':email', $user['email']);
        $statement->bindValue(':password', password_hash($user['password'], PASSWORD_DEFAULT));
        $statement->bindValue(':pseudo', $user['pseudo']);
        $statement->bindValue(':firstname', $user['firstname']);
        $statement->bindValue(':lastname', $user['lastname']);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
}
