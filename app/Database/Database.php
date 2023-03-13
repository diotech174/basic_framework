<?php
namespace App\Database;
use App\BasicExceptions\BasicException;

class Database
{
    public $conn = null;

    public function __construct()
    {
        $servername = $_ENV["MYSQL_SERVER"] ?? null;
        $database = $_ENV["DATABASE"] ?? null;
        $username = $_ENV["USER"] ?? null;
        $password = $_ENV["PASSWORD"] ?? null;

        try {
            
            $this->conn = new \PDO("mysql:host=$servername;dbname=$database", $username, $password);

        } catch (\PDOException $e) {

            throw new BasicException("Connection failed: " . $e->getMessage());
        }
  
    }
}