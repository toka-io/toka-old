<?php
require_once(__DIR__ . '/../../controller/IdentityController.php');

$response = array();
$controller = new IdentityController();

$controller->request();

header('Content-Type: ' . $controller->getContentType());

echo $response;