<?php
require_once(__DIR__ . '/../../../controller/IdentityController.php');

$controller = new IdentityController();

if ($_SERVER['REQUEST_METHOD'] === 'GET')
    $controller->get();
else if ($_SERVER['REQUEST_METHOD'] === 'POST')
    $controller->post();

header("Location: http://" . $_SERVER['SERVER_NAME']);