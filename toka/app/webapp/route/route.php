<?php
require_once('vendor/autoload.php');

require_once('controller/BaseController.php');
require_once('controller/CategoryController.php');
require_once('controller/ChatroomController.php');
require_once('controller/HomeController.php');
require_once('controller/IdentityController.php');
require_once('controller/ProfileController.php');
require_once('controller/RSController.php');
require_once('controller/SettingsController.php');

require_once('service/SessionService.php');

require_once('utility/ResponseCode.php');
require_once('utility/MediaType.php');

$GLOBALS['config'] = include('resource/config.php');

$controllers = array();

$controllers = array(
    '' => new HomeController(),
    'category' => new CategoryController(),
    'chatroom' => new ChatroomController(),
    'error' => new HomeController(),
    'faq' => new HomeController(),
    'login' => new IdentityController(),
    'logout' => new IdentityController(),
    'password' => new IdentityController(),
    'profile' => new ProfileController(),
    'signup' => new IdentityController(),
    'rs' => new RSController(),
    'settings' => new SettingsController()
);

// Security Layer
$sessionService = new SessionService();
$sessionService->initialize();

$service = BaseController::getService($_SERVER['REQUEST_URI']);

if (isset($controllers[$service])) {
    $controllers[$service]->request();
}
else
{
    http_response_code(404);
    include("error/404.php");
    exit();
}
