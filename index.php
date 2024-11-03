<?php

use Controller\ProductController;

require_once 'Db.php';
require_once 'Controller/ProductController.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");



$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


if ($method === "OPTIONS") {
    http_response_code(200);
    exit;
}

$db = new Db();

if ($uri === "/create" && $method === "POST") {
    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData);
    $controller = new ProductController();
    $controller->create($data);
} elseif ($uri === "/display" && $method === "GET") {

    $controller = new ProductController();
    $products = $controller->display();
    header('Content-Type: application/json');
    echo json_encode($products);
} elseif ($uri === "/massdelete" && $method === "POST") {

    $inputData = json_decode(file_get_contents('php://input'), true);
    $controller = new ProductController();
    $controller->massdelete($inputData['idarray']);
}

$db->closeConnection();
