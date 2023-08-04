<?php

namespace App\Database;

class DatabaseConnection
{
    private $serverName;
    private $username;
    private $password;
    private $dbName;

    public function __construct()
    {
        $this->serverName = '212.113.122.216';
        $this->username = 'gen_user';
        $this->password = '<>LG&c2>]nw%9F';
        $this->dbName = 'default_db';

        $this->connection();
    }

    private function connection()
    {
        try {
            $conn = new \PDO("pgsql:host=$this->serverName;dbname=$this->dbName", $this->username, $this->password);
            // set the PDO error mode to exception
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        catch(\PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}