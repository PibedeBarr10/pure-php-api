<?php

namespace App\Controller\Entity;

use App\Repository\DatabaseRepository;
use App\Service\JsonResponse;

class DeleteEntityController
{
    private DatabaseRepository $databaseRepository;

    public function __construct(DatabaseRepository $databaseRepository)
    {
        $this->databaseRepository = $databaseRepository;
    }

    public function deleteEntity(int $id): JsonResponse
    {
        $data = array('id', 'first_name', 'last_name', 'city');

        $result = $this->databaseRepository->find($id, $data);

        if (!$result) {
            return $this->notFoundEntity();
        }

        $this->databaseRepository->delete($id);

        $jsonResponse = new JsonResponse();
        $jsonResponse->setStatus('HTTP/1.1 200 OK');
        $jsonResponse->setBodyResponse(array('Usunięto encję'));

        return $jsonResponse;
    }

    private function notFoundEntity(): JsonResponse
    {
        $jsonResponse = new JsonResponse();
        $jsonResponse->setStatus('HTTP/1.1 404 Not Found');
        $jsonResponse->setBodyResponse(array('Nie ma takiej encji'));

        return $jsonResponse;
    }
}