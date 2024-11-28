<?php

namespace Src\Migrations;

require_once __DIR__ . '/../Database/Database.php';
use Src\Database\Database;

class CreateTables
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    // Migrates the database
    public function run()
    {
        $tables = [
            'roles' => "
            CREATE TABLE IF NOT EXISTS roles (
                id INT AUTO_INCREMENT PRIMARY KEY,
                role_name VARCHAR(255) NOT NULL
            );
        ",
            'users' => "
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                surname VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                role_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (role_id) REFERENCES roles(id)
            );
        ",
            'vote_categories' => "
            CREATE TABLE IF NOT EXISTS vote_categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                category_name VARCHAR(255) NOT NULL
            );
        ",
            'votes' => "
            CREATE TABLE IF NOT EXISTS votes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                voter_id INT NOT NULL,
                nominee_id INT NOT NULL,
                category_id INT NOT NULL,
                comment TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE (voter_id, nominee_id),
                FOREIGN KEY (voter_id) REFERENCES users(id),
                FOREIGN KEY (nominee_id) REFERENCES users(id),
                FOREIGN KEY (category_id) REFERENCES vote_categories(id),
                CONSTRAINT check_self_vote CHECK (voter_id != nominee_id)
            );
        "
        ];

        $createdTables = [];
        $existingTables = [];

        try {
            foreach ($tables as $tableName => $createQuery) {
                // Check if the table already exists
                $query = $this->db->query("SHOW TABLES LIKE '$tableName'");
                if ($query->rowCount() > 0) {
                    $existingTables[] = $tableName;
                } else {
                    // Create the table if it doesn't exist
                    $this->db->exec($createQuery);
                    $createdTables[] = $tableName;
                }
            }

            if (!empty($createdTables)) {
                echo "Tables created: " . implode(', ', $createdTables) . PHP_EOL;
            } else {
                echo "No new tables created." . PHP_EOL;
            }

            if (!empty($existingTables)) {
                echo "Tables already exist: " . implode(', ', $existingTables) . PHP_EOL;
            }
        } catch (\PDOException $e) {
            echo "Error creating tables: " . $e->getMessage() . PHP_EOL;
        }
    }

}
