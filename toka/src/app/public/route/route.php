<?php
require_once('controller/BaseController.php');
require_once('controller/CategoryController.php');
require_once('controller/ChatroomController.php');
require_once('controller/HomeController.php');
require_once('controller/IdentityController.php');
require_once('controller/ProfileController.php');

require_once('service/SessionService.php');

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
$sessionService = new SessionService();
$sessionService->initialize();

$service = BaseController::getService($_SERVER['REQUEST_URI']);

if (isset($controllers[$service]))
    $controllers[$service]->request();
else
{
    header("HTTP/1.0 404 Not Found");
    include("/../view/error/404.php");
    exit();
}
