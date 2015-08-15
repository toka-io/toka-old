<?php
// @controller
require_once('BaseController.php');

// @service
require_once('service/IdentityService.php');
require_once('service/SessionService.php');

// @model
require_once('model/UserModel.php');

class SettingsController extends BaseController
{
    function __construct() 
    {
        parent::__construct();
    }

      /*
     * @desc: GET services for /settings
     */
    public function get() 
    {
        $request = array();
        $request['uri'] = $_SERVER['REQUEST_URI'];
        $request['headers'] = getallheaders();
        $response = array();
        $match = array();

        $sessionService = new SessionService();
        $identityService = new IdentityService();

        if (isset($_SESSION['user'])) {
            $user = unserialize($_SESSION['user']);
            $available = $identityService->isUsernameAvailable($user->username);
            
            if ($available) {
                header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
    	        include("page/settings.php");
        	    exit();
            } else {
                http_response_code(404);
                header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
                include("error/404.php");
                exit();
            }
        } else {
            http_response_code(404);
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("error/404.php");
            exit();
        }
    }

     /*
     * @desc: POST services for /settings
     */
    public function put()
    {
        $request = array();
        $request['uri'] = $_SERVER['REQUEST_URI'];
        $request['headers'] = getallheaders();
        $request['data'] = $_PUT;
        $response = array();
        $match = array();

        if (preg_match('/^\/settings\/update\/?$/', $request['uri'], $match)) { // @url: /settings/update

            $response = array();
            $response["test"] = "test";
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } else {
            
            $response['status'] = "-1";
            $response['statusMsg'] = "not a valid service";
            http_response_code(404);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
            
        }
    }

    public function request()
    {
        $response = array();
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $response = $this->get();
        } else if ($_SERVER['REQUEST_METHOD'] ===  'PUT'){
            $response = $this->put();
        } else {          
            $response['status'] = "-1";
            $response['statusMsg'] = "not a valid service";
            http_response_code(404);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
        }
        
        echo $response;
    }
}