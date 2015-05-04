<?php
require_once(__DIR__ . '/../../controller/IdentityController.php');

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

//if ($isAjax) {
    $response = array();
    $controller = new IdentityController();
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET')
        $response = $controller->get();
    else if ($_SERVER['REQUEST_METHOD'] === 'POST')
        $response = $controller->post();    
    
    header('Content-Type: ' . $controller->getContentType());
    
    echo $response;
// } else {
//     header("HTTP/1.0 404 Not Found");
//     require(__DIR__ . '/../view/error/404.php');
// }

