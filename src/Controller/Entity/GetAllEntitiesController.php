<?php

namespace App\Controller\Entity;

use App\Repository\DatabaseRepository;
use App\Service\JsonResponse;
use App\Service\Request;

class GetAllEntitiesController
{
    private DatabaseRepository $databaseRepository;
    private Request $request;

    public function __construct(DatabaseRepository $databaseRepository, Request $request)
    {
        $this->databaseRepository = $databaseRepository;
        $this->request = $request;
    }

    public function getAllEntities(): JsonResponse
    {
        $columns = array('id', 'first_name', 'last_name', 'city');

        if (array_key_exists('column', $this->request->getAllGetAttr())) {
            $requestedColumns = $this->request->getGetAttr('column');
            $requestedColumns = explode(", ",$requestedColumns);

            # Prevent SQL injection
            if ($this->isColumn($requestedColumns, $columns)) {
                $columns = $requestedColumns;
            }
        }

        $result = $this->databaseRepository->findAll($columns);

        $jsonResponse = new JsonResponse();
        $jsonResponse->setStatus('HTTP/1.1 200 OK');
        $jsonResponse->setBodyResponse($result);

        return $jsonResponse;
    }

    private function isColumn(array $requestedColumns, array $allColumns): bool
    {
        foreach ($requestedColumns as $column)
        {
            if (!in_array($column, $allColumns)) {
                return false;
            }
        }
        return true;
    }
}