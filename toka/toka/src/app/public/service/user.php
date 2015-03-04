<?php
require_once(__DIR__ . '/../../controller/IdentityController.php');

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($isAjax) {
    $response = array();
    $controller = new IdentityController();
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET')
        $response = $controller->get();
    else if ($_SERVER['REQUEST_METHOD'] === 'POST')
        $response = $controller->post();
    
    header($controller->getContentType());
    
    echo $response;
} else {
    header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404");
}

