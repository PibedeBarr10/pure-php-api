<?php

namespace App\Controller;

use App\Controller\Entity\AddEntityController;
use App\Controller\Entity\DeleteEntityController;
use App\Controller\Entity\GetAllEntitiesController;
use App\Controller\Entity\GetEntityController;
use App\Repository\DatabaseRepository;
use App\Service\Database;
use App\Service\JsonResponse;
use App\Service\Request;
use PDO;

class APIController
{
    private PDO $dbConnection;
    private DatabaseRepository $databaseRepository;
    private Request $request;
    private string $requestMethod;
    private int $userId = 0;

    public function __construct()
    {
        $database = new Database(
            $_ENV['DB_HOST'],
            $_ENV['DB_PORT'],
            $_ENV['DB_DATABASE'],
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD']
        );
        $this->dbConnection = $database->getConnection();

        $this->databaseRepository = new DatabaseRepository($this->dbConnection, "client");

        $this->request = new Request();
        $this->requestMethod = $this->request->getRequestedMethod();

        if (array_key_exists('id', $this->request->getAllGetAttr())) {
            $this->userId = $this->request->getGetAttr('id');
        }
    }

    public function processRequest(): JsonResponse
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->userId) {
                    $getEntityController = new GetEntityController(
                        $this->databaseRepository,
                        $this->request
                    );
                    $jsonResponse = $getEntityController->getEntity($this->userId);
                } else {
                    $getAllEntitiesController = new GetAllEntitiesController(
                        $this->databaseRepository,
                        $this->request
                    );
                    $jsonResponse = $getAllEntitiesController->getAllEntities();
                }
                break;
            case 'POST':
                $addEntityController = new AddEntityController(
                    $this->databaseRepository,
                    $this->request
                );
                $jsonResponse = $addEntityController->addEntity();
                break;
            case 'DELETE':
                $deleteEntityController = new DeleteEntityController($this->databaseRepository);
                $jsonResponse = $deleteEntityController->deleteEntity($this->userId);
                break;
            default:
                $jsonResponse = $this->notFoundMethod();
                break;
        }

        return $jsonResponse;
    }

    private function notFoundMethod(): JsonResponse
    {
        $jsonResponse = new JsonResponse();
        $jsonResponse->setStatus('HTTP/1.1 404 Not Found');
        $jsonResponse->setBodyResponse(array('Nie ma takiej operacji'));

        return $jsonResponse;
    }
}