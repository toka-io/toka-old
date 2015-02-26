<?php
// Maybe add an alias and require that alias file, then require using the name from the alias file...to have encapsulation
require_once(__DIR__ . '/../../controller/ChatroomController.php');

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($isAjax) {
    $response = array();
    $controller = new ChatroomController();
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET')
        $response = $controller->get();
    else if ($_SERVER['REQUEST_METHOD'] === 'POST')
        $response = $controller->post();
    
    header($controller->getContentType());
    
    echo $response;
} else {
    header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404");
}

