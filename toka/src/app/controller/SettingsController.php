<?php
// @controller
require_once('BaseController.php');

// @service
require_once('service/IdentityService.php');

// @model
require_once('model/UserModel.php');

class SettingsController extends BaseController
{
    function __construct() 
    {
        parent::__construct();
    }

      /*
     * @desc: GET services for /profile
     */
    public function get() 
    {
        $request = array();
        $request['uri'] = $_SERVER['REQUEST_URI'];
        $request['headers'] = getallheaders();
        $response = array();
        $match = array();

        $username = $match[1];
        
        $identityService = new IdentityService();
        $available = $identityService->isUsernameAvailable($username);
        
        if (!$avalible) {
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
	        include("page/settings.php");
    	    exit();
        } else {
            http_response_code(404);
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("error/404.php");
            exit();
        }
    }

    public function request()
    {
        $response = array();
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
            $response = $this->get();
        else {          
            $response['status'] = "-1";
            $response['statusMsg'] = "not a valid service";
            http_response_code(404);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
        }
        
        echo $response;
    }
}