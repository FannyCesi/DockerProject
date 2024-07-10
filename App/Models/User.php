<?php

namespace App\Models;

use App\Utility\Hash;
use Core\Model;
use PDO;
use Exception;

/**
 * Modèle User :
 */
class User extends Model {

    /**
     * Crée un utilisateur
     */
    public static function createUser($data) {
        $db = static::getDB();

        $stmt = $db->prepare('INSERT INTO users(username, email, password, salt) VALUES (:username, :email, :password, :salt)');

        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':salt', $data['salt']);

        $stmt->execute();

        return $db->lastInsertId();
    }

    /**
     * Récupère un utilisateur par login
     */
    public static function getByLogin($login) {
        $db = static::getDB();

        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");

        $stmt->bindParam(':email', $login);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Connecte un utilisateur
     * @param int $id
     * @access public
     * @return array|boolean
     * @throws Exception
     */
    public static function login($id) {
        $db = static::getDB();

        $stmt = $db->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
