<?php

require "../vendor/autoload.php";

use App\Controller\APIController;
use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$API = new APIController();
$result = $API->processRequest();

echo $result->getResponse();

