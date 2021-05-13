<?php


namespace App\Controller\Entity;


use App\Repository\DatabaseRepository;
use App\Service\JsonResponse;
use PDO;

class DeleteEntityController
{
    private DatabaseRepository $databaseRepository;
    private JsonResponse $jsonResponse;

    public function __construct(PDO $dbConnection, string $tableName)
    {
        $this->databaseRepository = new DatabaseRepository($dbConnection, $tableName);
        $this->jsonResponse = new JsonResponse();
    }

    public function deleteEntity($id): JsonResponse
    {
        $result = $this->databaseRepository->find($id);

        if (!$result) {
            return $this->notFoundEntity();
        }

        $this->databaseRepository->delete($id);

        $this->jsonResponse->setStatus('HTTP/1.1 200 OK');
        $this->jsonResponse->setBodyResponse(array('Usunięto encję'));

        return $this->jsonResponse;
    }

    private function notFoundEntity(): JsonResponse
    {
        $this->jsonResponse->setStatus('HTTP/1.1 404 Not Found');
        $this->jsonResponse->setBodyResponse(array('Nie ma takiej encji'));

        return $this->jsonResponse;
    }
}