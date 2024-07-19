<?php

require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/controllers/countrycontroller.php';
require __DIR__ . '/../src/controllers/tripcontroller.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if (preg_match('/^\/countries(?:\/(\d+))?$/', $path, $matches)) {
    $controller = new CountryController();
    if ($method === 'POST') {
        $controller->create();
    } elseif ($method === 'PUT' && isset($matches[1])) {
        $controller->update($matches[1]);
    } elseif ($method === 'DELETE' && isset($matches[1])) {
        $controller->delete($matches[1]);
    }
} elseif (preg_match('/^\/trips(?:\/(\d+))?$/', $path, $matches)) {
    $controller = new TripController();
    if ($method === 'POST') {
        $controller->create();
    } elseif ($method === 'PUT' && isset($matches[1])) {
        $controller->update($matches[1]);
    } elseif ($method === 'DELETE' && isset($matches[1])) {
        $controller->delete($matches[1]);
    } elseif ($method === 'GET' && !isset($matches[1])) {
        $controller->getAll();
    }
} elseif ($method === 'GET' && preg_match('/^\/trips\/filter$/', $path)) {
    $controller = new TripController();
    $controller->filter();
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}
