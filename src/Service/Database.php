<?php

namespace App\Service;

use PDO;
use PDOException;

class Database
{
    private string $host;
    private string $port;
    private string $db_name;
    private string $username;
    private string $password;
    private PDO $dbConnection;

    public function __construct(
        string $host,
        string $port,
        string $db_name,
        string $username,
        string $password
    )
    {
        $this->host = $host;
        $this->port = $port;
        $this->db_name = $db_name;
        $this->username = $username;
        $this->password = $password;

        try {
            $this->dbConnection = new PDO(
                "mysql:host=$this->host;port=$this->port;charset=utf8mb4;dbname=$this->db_name",
                $this->username,
                $this->password
            );
            $this->dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->dbConnection;
    }
}