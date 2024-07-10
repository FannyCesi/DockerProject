<?php
/**./vendor/bin/phpunit */
use PHPUnit\Framework\TestCase;
use App\Models\User;
use Core\Model;

class UserRegistrationTest extends TestCase
{
    /**
     * Test l'inscription d'un utilisateur
     */
    public function testUserRegistration()
    {
        // Configuration des données d'inscription
        $data = [
            'username' => 'testuser',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password-check' => 'password123'
        ];

        // Passer un tableau vide ou les paramètres de route nécessaires au constructeur
        $route_params = [];
        $userController = new \App\Controllers\User($route_params);

        // Appel de la fonction register pour enregistrer l'utilisateur
        $userId = $this->invokeMethod($userController, 'register', [$data]);

        // Vérification que l'utilisateur a bien été enregistré
        $this->assertNotFalse($userId);

        // Récupération de l'utilisateur depuis la base de données
        $db = Model::getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification des données de l'utilisateur
        $this->assertEquals($data['username'], $user['username']);
        $this->assertEquals($data['email'], $user['email']);
        $this->assertTrue(\App\Utility\Hash::verify($data['password'], $user['password'], $user['salt']));
    }

    /**
     * Méthode utilitaire pour appeler des méthodes privées/protégées
     */
    protected function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }
}
