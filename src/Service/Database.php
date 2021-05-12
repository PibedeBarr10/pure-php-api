<?php

namespace App\Service;

class Database
{
    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;
    private $dbConnection = null;

    public function __construct(
        $host,
        $port,
        $db_name,
        $username,
        $password
    )
    {
        $this->host = $host;
        $this->port = $port;
        $this->db_name = $db_name;
        $this->username = $username;
        $this->password = $password;

        try {
            $this->dbConnection = new \PDO(
                "mysql:host=$this->host;port=$this->port;charset=utf8mb4;dbname=$this->db_name",
                $this->username,
                $this->password
            );
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getConnection() {
        return $this->dbConnection;
    }
}