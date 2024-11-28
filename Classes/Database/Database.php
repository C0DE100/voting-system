<?php

namespace Src\Database;

require_once __DIR__ . '/../../config/database.php';

use PDO;
use PDOException;

class Database
{
    private $connection;

    public function __construct()
    {
        // Credentials
        $host = DB_HOST;
        $dbname = DB_NAME;
        $user = DB_USER;
        $password = DB_PASS;

        try {
            $this->connection = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
