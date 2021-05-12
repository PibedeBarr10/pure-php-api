<?php

require "../vendor/autoload.php";

use App\Controller\ClientController;
use App\Service\Database;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$database = new Database(
    $_ENV['DB_HOST'],
    $_ENV['DB_PORT'],
    $_ENV['DB_DATABASE'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD'],
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
