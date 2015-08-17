<?php
require_once('controller/BaseController.php');
require_once('controller/CategoryController.php');
require_once('controller/ChatroomController.php');
require_once('controller/HomeController.php');
require_once('controller/IdentityController.php');
require_once('controller/ProfileController.php');
require_once('controller/SettingsController.php');

require_once('service/SessionService.php');

$controllers = array();

$controllers = array(
    '' => new HomeController(),
    'category' => new CategoryController(),
    'chatroom' => new ChatroomController(),
    'faq' => new HomeController(),
    'login' => new IdentityController(),
    'logout' => new IdentityController(),
    'password' => new IdentityController(),
    'profile' => new ProfileController(),
    'signup' => new IdentityController(),
    'user' => new IdentityController(),
    'settings' => new SettingsController()
);

// Security Layer
$sessionService = new SessionService();
$sessionService->initialize();

$service = BaseController::getService($_SERVER['REQUEST_URI']);

if (isset($controllers[$service]))
    $controllers[$service]->request();
else
{
    http_response_code(404);
    include("error/404.php");
    exit();
}
