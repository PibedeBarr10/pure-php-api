<?php


namespace App\Controller;

use App\Controller\Entity\AddEntityController;
use App\Controller\Entity\DeleteEntityController;
use App\Controller\Entity\GetAllEntitiesController;
use App\Controller\Entity\GetEntityController;
use App\Service\Database;
use App\Service\JsonResponse;
use App\Service\Request;
use PDO;

class APIController
{
    private PDO $dbConnection;
    private JsonResponse $jsonResponse;
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
            $_ENV['DB_PASSWORD'],
        );
        $this->dbConnection = $database->getConnection();

        $this->request = new Request();
        $this->requestMethod = $this->request->getServerAttr("REQUEST_METHOD");

        if (array_key_exists('id', $this->request->getAllGetAttr())) {
            $this->userId = $this->request->getGetAttr('id');
        }

        $this->jsonResponse = new JsonResponse();
    }

    public function processRequest(): string
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->userId) {
                    $getEntityController = new GetEntityController($this->dbConnection, "client");
                    $this->jsonResponse = $getEntityController->getEntity($this->userId);
                } else {
                    $getAllEntitiesController = new GetAllEntitiesController($this->dbConnection, "client");
                    $this->jsonResponse = $getAllEntitiesController->getAllEntities();
                }
                break;
            case 'POST':
                $addEntityController = new AddEntityController($this->dbConnection, "client");
                $this->jsonResponse = $addEntityController->addEntity();
                break;
            case 'DELETE':
                $deleteEntityController = new DeleteEntityController($this->dbConnection, "client");
                $this->jsonResponse = $deleteEntityController->deleteEntity($this->userId);
                break;
            default:
                $this->notFoundEntity();
                break;
        }

        return $this->jsonResponse->getResponse();
    }

    private function notFoundEntity(): JsonResponse
    {
        $this->jsonResponse->setStatus('HTTP/1.1 404 Not Found');
        $this->jsonResponse->setBodyResponse(array('Nie ma takiej encji'));

        return $this->jsonResponse;
    }
}