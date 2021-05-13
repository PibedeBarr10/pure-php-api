<?php


namespace App\Controller\Entity;


use App\Repository\DatabaseRepository;
use App\Service\JsonResponse;
use App\Service\Request;
use PDO;

class GetAllEntitiesController
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

    public function getAllEntities(): JsonResponse
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

        $result = $this->databaseRepository->findAll($data);

        $this->jsonResponse->setStatus('HTTP/1.1 200 OK');
        $this->jsonResponse->setBodyResponse($result);

        return $this->jsonResponse;
    }

    private function isColumn(array $requestData, array $data): bool
    {
        foreach ($requestData as $str)
        {
            if (!in_array($str, $data)) {
                return false;
            }
        }
        return true;
    }
}