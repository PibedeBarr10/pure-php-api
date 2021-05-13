<?php


namespace App\Service;

class JsonResponse
{
    private array $response;

    public function __construct()
    {
        header("Content-Type:application/json");
        $this->response = array();
    }

    public function setStatus(string $status): void
    {
        $this->response['status'] = $status;
    }

    public function setBodyResponse(array $body): void
    {
        $this->response['body'] = $body;
    }

    public function getResponse(): string
    {
        return json_encode($this->response);
    }
}