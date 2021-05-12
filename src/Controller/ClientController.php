<?php

namespace App\Controller;

use App\Repository\ClientRepository;

class ClientController
{
    private $db;
    private $requestMethod;
    private $userId;
    private ClientRepository $clientRepository;

    public function __construct(
        $db,
        $requestMethod,
        $userId
    )
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->userId = $userId;
        $this->clientRepository = new ClientRepository($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->userId) {
                    $response = $this->getClient($this->userId);
                } else {
                    $response = $this->getAllClients();
                };
                break;
            case 'POST':
                $response = $this->addClient();
                break;
            case 'DELETE':
                $response = $this->deleteUser($this->userId);
                break;
            default:
                $response = $this->notFoundClient();
                break;
        }

        header("Content-Type:application/json");
        header($response['status']);

        return $response;
    }

    private function getClient($id)
    {
        $result = $this->clientRepository->find($id);

        if (!$result) {
            return $this->notFoundClient();
        }

        $response['status'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);

        return $response;
    }

    private function getAllClients()
    {
        $result = $this->clientRepository->findAll();
        $response['status'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);

        return $response;
    }

    private function notFoundClient()
    {
        $response['status'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = "Nie ma takiego klienta";

        return $response;
    }

    private function addClient()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$this->validate($input)) {
            return $this->errorResponse();
        }

        $this->clientRepository->create($input);
        $response['status'] = 'HTTP/1.1 201 Created';
        $response['body'] = 'Dodano klienta';

        return $response;
    }

    private function validate($input)
    {
        if (!isset($input['first_name'])
            || !isset($input['last_name'])
            || !isset($input['city'])
        ) {
            return false;
        }

        return true;
    }

    private function errorResponse()
    {
        $response['status'] = 'HTTP/1.1 400';
        $response['body'] = json_encode([
            'error' => 'Brak wymaganych danych'
        ]);

        return $response;
    }

    private function deleteUser($id)
    {
        $result = $this->clientRepository->find($id);
        if (!$result) {
            return $this->notFoundClient();
        }
        $this->clientRepository->delete($id);
        $response['status'] = 'HTTP/1.1 200 OK';
        $response['body'] = "Usunięto klienta";
        return $response;
    }
}