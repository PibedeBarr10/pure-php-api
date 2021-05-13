<?php


namespace App\Repository;

use PDO;
use PDOException;

class DatabaseRepository
{
    private PDO $db;
    private string $tableName;

    public function __construct(PDO $db, string $tableName)
    {
        $this->db = $db;
        $this->tableName = $tableName;
    }

    public function find(int $id, array $data): array
    {
        $statement = "
            SELECT " . implode(', ', $data) . "
            FROM " . $this->tableName . "
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => $id
            ));

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function findAll(array $data): array
    {
        $statement = "
            SELECT " . implode(', ', $data) . " 
            FROM " . $this->tableName .";
        ";

        try {
            $statement = $this->db->query($statement);

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function create(array $input): void
    {
        $statement = "
            INSERT INTO " . $this->tableName . " (first_name, last_name, city)
            VALUES (:first_name, :last_name, :city);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'city' => $input['city']
            ));
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete(int $id): void
    {
        $statement = "
            DELETE FROM " . $this->tableName . "
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => $id
            ));
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}