<?php

namespace App\Models;

use Core\Model;
use DateTime;
use Exception;

/**
 * Articles Model
 */
class Articles extends Model {

    public static function getAll($filter) {
        $db = static::getDB();

        $query = 'SELECT * FROM articles ';

        switch ($filter) {
            case 'views':
                $query .= ' ORDER BY articles.views DESC';
                break;
            case 'date':
                $query .= ' ORDER BY articles.published_date DESC';
                break;
            case '':
                break;
        }

        $stmt = $db->query($query);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getOne($id) {
        $db = static::getDB();

        $stmt = $db->prepare('
            SELECT * FROM articles
            INNER JOIN users ON articles.user_id = users.id
            WHERE articles.id = ?
            LIMIT 1');

        $stmt->execute([$id]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function addOneView($id) {
        $db = static::getDB();

        $stmt = $db->prepare('
            UPDATE articles
            SET articles.views = articles.views + 1
            WHERE articles.id = ?');

        $stmt->execute([$id]);
    }

    public static function getByUser($id) {
        $db = static::getDB();

        $stmt = $db->prepare('
            SELECT *, articles.id as id FROM articles
            LEFT JOIN users ON articles.user_id = users.id
            WHERE articles.user_id = ?');

        $stmt->execute([$id]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getSuggest() {
        $db = static::getDB();

        $stmt = $db->prepare('
            SELECT *, articles.id as id FROM articles
            INNER JOIN users ON articles.user_id = users.id
            ORDER BY published_date DESC LIMIT 10');

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function save($data) {
        $db = static::getDB();

        $stmt = $db->prepare('INSERT INTO articles(name, description, user_id, published_date) VALUES (:name, :description, :user_id, :published_date)');

        $published_date = new DateTime();
        $published_date = $published_date->format('Y-m-d');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':published_date', $published_date);
        $stmt->bindParam(':user_id', $data['user_id']);

        $stmt->execute();

        return $db->lastInsertId();
    }

    public static function attachPicture($articleId, $pictureName) {
        $db = static::getDB();

        $stmt = $db->prepare('UPDATE articles SET picture = :picture WHERE articles.id = :articleid');

        $stmt->bindParam(':picture', $pictureName);
        $stmt->bindParam(':articleid', $articleId);

        $stmt->execute();
    }

    public static function update($id, $data) {
        $db = static::getDB();

        $stmt = $db->prepare('
            UPDATE articles
            SET name = :name, description = :description, picture = :picture, published_date = :published_date
            WHERE id = :id
        ');

        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':picture', $data['picture']);
        $published_date = new DateTime();
        $published_date = $published_date->format('Y-m-d');
        $stmt->bindParam(':published_date', $published_date);
        $stmt->bindParam(':id', $id);

        $stmt->execute();
    }

    public static function delete($id, $user_id) {
        $db = static::getDB();

        $stmt = $db->prepare('
            DELETE FROM articles
            WHERE id = :id AND user_id = :user_id
        ');

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();
    }
}
