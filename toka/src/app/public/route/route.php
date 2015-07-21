<?php
require_once(__DIR__ . '/../../controller/BaseController.php');
require_once(__DIR__ . '/../../controller/CategoryController.php');
require_once(__DIR__ . '/../../controller/ChatroomController.php');
require_once(__DIR__ . '/../../controller/HomeController.php');
require_once(__DIR__ . '/../../controller/IdentityController.php');
require_once(__DIR__ . '/../../controller/ProfileController.php');

$controllers = array();

$controllers = array(
    '' => new HomeController(),
    'category' => new CategoryController(),
    'chatroom' => new ChatroomController(),
    'login' => new IdentityController(),
    'logout' => new IdentityController(),
    'profile' => new ProfileController(),
    'signup' => new IdentityController(),
    'user' => new IdentityController()
);

$service = BaseController::getService($_SERVER['REQUEST_URI']);

$controllers[$service]->request();
