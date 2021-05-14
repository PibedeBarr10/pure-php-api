<?php

namespace App\Service;

class Request
{
    private array $server;
    private array $get;
    private array $post;

    public function __construct()
    {
        $this->server = $_SERVER;
        $this->get = $_GET;
        $this->post = $_POST;
    }

    public function getRequestedMethod(): string
    {
        return $this->server["REQUEST_METHOD"];
    }

    public function getGetAttr(string $key): string
    {
        return $this->get[$key];
    }

    public function getPostAttr(string $key): string
    {
        return $this->post[$key];
    }

    public function getAllGetAttr(): array
    {
        return $this->get;
    }
}