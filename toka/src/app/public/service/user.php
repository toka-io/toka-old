<?php
require_once(__DIR__ . '/../../controller/IdentityController.php');

$controller = new IdentityController();

$response = $controller->request();

