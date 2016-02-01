<?php
/* GLOBAL INCLUDES */
require_once('vendor/autoload.php');

require_once('controller/Controller.php');
require_once('controller/CategoryController.php');
require_once('controller/ChatroomController.php');
require_once('controller/HomeController.php');
require_once('controller/IdentityController.php');
require_once('controller/PasswordController.php');
require_once('controller/ProfileController.php');
require_once('controller/APIController.php');
require_once('controller/SettingsController.php');

require_once('controller/internal/AnalyticsController.php');
require_once('controller/internal/TestController.php');

require_once('service/SessionService.php');

require_once('utility/KeyGen.php');
require_once('utility/MediaType.php');
require_once('utility/RequestMapping.php');
require_once('utility/ResponseCode.php');
require_once('utility/TimeUtility.php');

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
    'test' => new TestController()
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
