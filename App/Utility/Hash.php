<?php

namespace App\Utility;

/**
 * Hash:
 */
class Hash {

    /**
     * Génère et retourne un hash
     */
    public static function generate($string, $salt = "") {
        return(hash("sha256", $string . $salt));
    }

    /**
     * Génère et retourne un salt
     */
    public static function generateSalt($length) {
        $salt = "";
        $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789/\\][{}\'\";:?.>,<!@#$%^&*()-_=+|";
        for ($i = 0; $i < $length; $i++) {
            $salt .= $charset[mt_rand(0, strlen($charset) - 1)];
        }
        return $salt;
    }

    /**
     * Vérifie si le mot de passe en clair correspond au hash avec le sel donné.
     *
     * @param string $password Le mot de passe en clair à vérifier
     * @param string $hash Le hash du mot de passe stocké en base de données
     * @param string $salt Le sel utilisé lors du hachage du mot de passe
     * @return bool true si le mot de passe correspond, sinon false
     */
    public static function verify($password, $hash, $salt)
    {
        $hashedPassword = self::generate($password, $salt);
        return hash_equals($hashedPassword, $hash);
    }

    /**
     * Génère et retourne un UID
     */
    public static function generateUnique() {
        return(self::generate(uniqid()));
    }

}
