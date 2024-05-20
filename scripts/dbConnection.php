<?php // establish connection to database, otherwise exit

declare(strict_types=1);

class DbConnection extends PDO
{
    public function __construct()
    {
        try {
            // connect to database (and set error mode)
            parent::__construct("mysql:host=localhost;dbname=notes", 'notes', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
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
