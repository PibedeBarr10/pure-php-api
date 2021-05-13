<?php


namespace App\Controller\Entity;


use App\Repository\DatabaseRepository;
use App\Service\JsonResponse;
use App\Service\Request;
use PDO;

class GetEntityController
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

    public function getEntity(int $id): JsonResponse
    {
        $data = array('id', 'first_name', 'last_name', 'city');

        if (array_key_exists('column', $this->request->getAllGetAttr())) {
            $requestData = $this->request->getGetAttr('column');
            $requestData = explode(", ",$requestData);

            # Prevent SQL injection
            if ($this->isColumn($requestData, $data)) {
                $data = $requestData;
            }
        }

        $result = $this->databaseRepository->find($id, $data);

        if (!$result) {
            return $this->notFoundEntity();
        }

        $this->jsonResponse->setStatus('HTTP/1.1 200 OK');
        $this->jsonResponse->setBodyResponse($result);

        return $this->jsonResponse;
    }

    private function isColumn(array $requestData, array $data): bool
    {
        foreach ($requestData as $value)
        {
            if (!in_array($value, $data)) {
                return false;
            }
        }
        return true;
    }

    private function notFoundEntity(): JsonResponse
    {
        $this->jsonResponse->setStatus('HTTP/1.1 404 Not Found');
        $this->jsonResponse->setBodyResponse(array('Nie ma takiej encji'));

        return $this->jsonResponse;
    }
}