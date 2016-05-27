<?php
/* GLOBAL INCLUDES */
require_once('vendor/autoload.php');

require_once('resource/route-config.php');

/*******************************************************************************
 * Enable Exceptions to be Thrown & Exception Handler
 ******************************************************************************/
function exception_error_handler($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        // This error code is not included in error_reporting
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler("exception_error_handler");

/*******************************************************************************
 * GLOBAL CONFIGURATION
 ******************************************************************************/
$GLOBALS['config'] = include('resource/config.php');

/*******************************************************************************
 * CONTROLLER MAPPING
 ******************************************************************************/
$controllers = array();

$controllers = array(
    '' => new HomeController(),
    'analytics' => new AnalyticsController(),
    'api' => new APIController(),
    'category' => new CategoryController(),
    'chatroom' => new ChatroomController(),
    'error' => new HomeController(),
    'faq' => new HomeController(),
    'login' => new IdentityController(),
    'logout' => new IdentityController(),
    'password' => new PasswordController(),
    'profile' => new ProfileController(),
    'signup' => new IdentityController(),
    'settings' => new SettingsController(),
	'task' => new TaskController(),
    'test' => new TestController(),
    'redesign' => new RedesignController()
);

/*******************************************************************************
 * SECURITY LAYER
 ******************************************************************************/
SessionService::initialize();

/*******************************************************************************
 * GLOBAL DATA LAYER
 ******************************************************************************/
$response = CategoryService::getAllCategories(array());
$_SESSION['categories'] = serialize($response['data']);

/*******************************************************************************
 * REQUEST HANDLER
 ******************************************************************************/
$service = Controller::getService($_SERVER['REQUEST_URI']);

if (isset($controllers[$service])) {
    SessionService::updatePageHistory();
    $controllers[$service]->request();
}
else {
    http_response_code(404);
    include("error/404.php");
    exit();
}
