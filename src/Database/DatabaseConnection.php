<?php

namespace App\Database;

use Dotenv\Dotenv;

class DatabaseConnection
{
    private $serverName;
    private $username;
    private $password;
    private $dbName;

    public function __construct()
    {
        Dotenv::createImmutable(dirname(__DIR__, 2))->load();

        $this->serverName = $_ENV['DB_HOST'];
        $this->username = $_ENV['DB_USERNAME'];
        $this->password = $_ENV['DB_PASSWORD'];
        $this->dbName = $_ENV['DB_NAME'];

        $this->connection();
    }

    public function connection()
    {
        try {
            $conn = new \PDO("pgsql:host=$this->serverName;dbname=$this->dbName", $this->username, $this->password);
            // set the PDO error mode to exception
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return $conn;
        }
        catch(\PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}