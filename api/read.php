<?php

require "../vendor/autoload.php";

use App\Controller\ClientController;
use App\Service\Database;

$database = new Database(
    "www.pure-php-app.com",
    3306,
    "pure-php-app",
    "admin",
    "12345"
);
$db = $database->getConnection();

$requestMethod = $_SERVER["REQUEST_METHOD"];

$userId = null;
if (isset($_GET['id'])) {
    $userId = $_GET['id'];
}

$clientController = new ClientController($db, $requestMethod, $userId);
$result = $clientController->processRequest();

echo $result['body'];

