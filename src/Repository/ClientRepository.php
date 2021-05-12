<?php

namespace App\Repository;

class ClientRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function find(int $id): array
    {
        $statement = "
            SELECT id, first_name, last_name, city
            FROM client
            WHERE id = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function findAll()
    {
        $statement = "
            SELECT * 
            FROM client;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function create($input)
    {
        $statement = "
            INSERT INTO client (first_name, last_name, city)
            VALUES (:first_name, :last_name, :city);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'city' => $input['city']
            ));
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM client
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}