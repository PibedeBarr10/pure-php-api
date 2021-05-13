<?php

namespace App\Controller\Entity;

use App\Repository\DatabaseRepository;
use App\Service\JsonResponse;
use App\Service\Request;
use PDO;

class AddEntityController
{
    private DatabaseRepository $databaseRepository;
    private Request $request;
    private JsonResponse $jsonResponse;

    public function __construct(PDO $dbConnection, string $tableName)
    {
        $this->databaseRepository = new DatabaseRepository($dbConnection, $tableName);
        $this->request = new Request();
        $this->jsonResponse = new JsonResponse();
    }

    public function addEntity(): JsonResponse
    {
        $input = $this->request->getAllGetAttr();

        if ($this->validate($input)) {
            return $this->errorResponse();
        }

        $this->databaseRepository->create($input);

        $this->jsonResponse->setStatus('HTTP/1.1 201 Created');
        $this->jsonResponse->setBodyResponse(array('Dodano klienta'));

        return $this->jsonResponse;
    }

    private function validate(array $input): bool
    {
        return (empty($input['first_name'])
            || empty($input['last_name'])
            || empty($input['city'])
        );
    }

    private function errorResponse(): JsonResponse
    {
        $this->jsonResponse->setStatus('HTTP/1.1 400');
        $this->jsonResponse->setBodyResponse(array('Brak wymaganych danych'));

        return $this->jsonResponse;
    }
}