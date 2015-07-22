<?php
require_once(__DIR__ . '/../../controller/BaseController.php');
require_once(__DIR__ . '/../../controller/CategoryController.php');
require_once(__DIR__ . '/../../controller/ChatroomController.php');
require_once(__DIR__ . '/../../controller/HomeController.php');
require_once(__DIR__ . '/../../controller/IdentityController.php');
require_once(__DIR__ . '/../../controller/ProfileController.php');

require_once(__DIR__ . '/../../service /SecurityService.php');

$controllers = array();

$controllers = array(
    '' => new HomeController(),
    'category' => new CategoryController(),
    'chatroom' => new ChatroomController(),
    'faq' => new HomeController(),
    'login' => new IdentityController(),
    'logout' => new IdentityController(),
    'profile' => new ProfileController(),
    'signup' => new IdentityController(),
    'user' => new IdentityController()
);

// Security Layer
$securityService = new SecurityService();
$securityService->initialize();

$service = BaseController::getService($_SERVER['REQUEST_URI']);

$controllers[$service]->request();
