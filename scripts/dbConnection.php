<?php

declare(strict_types=1);

// handle requests to the database, but do not connect to the database until it is needed
class DbConnection
{
    private $pdo = null;

    // find the first (there should be only one) user with the given name
    // returns false or ['user' => '...', 'password' => '...']
    public function findUser(string $user): array|bool
    {
        $stmt = $this->getPDO()->prepare("SELECT `password` FROM `user` WHERE `user` = :user");
        $stmt->bindParam(':user', $user);
        $stmt->execute();
        return $stmt->fetch(); // fetch() returns false if no row exists, or the first row
    }

    private function getPDO(): PDO
    {
        return $this->pdo ?? $this->createPDO();
    }

    private function createPDO(): PDO
    {
        try {
            // connect to database (and set error mode)
            return $this->pdo = new PDO("mysql:host=localhost;dbname=notes", 'notes', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (PDOException $e) { // connection failed
            // error message
            echo "Connecting to internal database failed.";
            http_response_code(503);
            // log exception
            file_put_contents('log/exception.log', $e->getMessage() . "\n", FILE_APPEND);
            exit;
        }
    }
}
