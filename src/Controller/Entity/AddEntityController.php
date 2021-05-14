<?php

namespace App\Controller\Entity;

use App\Repository\DatabaseRepository;
use App\Service\JsonResponse;
use App\Service\Request;

class AddEntityController
{
    private DatabaseRepository $databaseRepository;
    private Request $request;

    public function __construct(DatabaseRepository $databaseRepository, Request $request)
    {
        $this->databaseRepository = $databaseRepository;
        $this->request = $request;
    }

    public function addEntity(): JsonResponse
    {
        $input = $this->request->getAllGetAttr();

        if ($this->validate($input)) {
            return $this->errorResponse();
        }

        $this->databaseRepository->create($input);

        $jsonResponse = new JsonResponse();
        $jsonResponse->setStatus('HTTP/1.1 201 Created');
        $jsonResponse->setBodyResponse(array('Dodano klienta'));

        return $jsonResponse;
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
        $jsonResponse = new JsonResponse();
        $jsonResponse->setStatus('HTTP/1.1 400');
        $jsonResponse->setBodyResponse(array('Brak wymaganych danych'));

        return $jsonResponse;
    }
}