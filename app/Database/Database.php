<?php
namespace App\Database;

class Database
{
    public $conn = null;

    public function __construct()
    {
        try {
            $servername = $_ENV["MYSQL_SERVER"] ?? null;
            $database = $_ENV["DATABASE"] ?? null;
            $username = $_ENV["USER"] ?? null;
            $password = $_ENV["PASSWORD"] ?? null;

            $this->conn = new \PDO("mysql:host=$servername;dbname=$database", $username, $password);
        } catch (\PDOException $e) {
            throw new \PDOException("Connection failed: " . $e->getMessage());
        }
    }
}