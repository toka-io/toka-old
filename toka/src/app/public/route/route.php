<?php
require_once(__DIR__ . '/../../controller/BaseController.php');
require_once(__DIR__ . '/../../controller/CategoryController.php');
require_once(__DIR__ . '/../../controller/ChatroomController.php');
require_once(__DIR__ . '/../../controller/IdentityController.php');

$controllers = array();

$controllers = array(
    'category' => new CategoryController(),
    'chatroom' => new ChatroomController(),
    'login' => new IdentityController(),
    'logout' => new IdentityController(),
    'signup' => new IdentityController(),
    'user' => new IdentityController()
);

$service = BaseController::getService($_SERVER['REQUEST_URI']);

$controllers[$service]->request();
