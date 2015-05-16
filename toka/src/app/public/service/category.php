<?php
require_once(__DIR__ . '/../../controller/CategoryController.php');


$response = array();
$controller = new CategoryController();

if ($_SERVER['REQUEST_METHOD'] === 'GET')
    $response = $controller->get();
else if ($_SERVER['REQUEST_METHOD'] === 'POST')
    $response = $controller->post();

header($controller->getContentType());

echo $response;