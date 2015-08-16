<?php
// @controller
require_once('BaseController.php');

// @service
require_once('service/IdentityService.php');
require_once('service/SessionService.php');

// @model
require_once('model/UserModel.php');

// @repo
require_once('repo/SettingsRepo.php');

class SettingsController extends BaseController
{
    function __construct() 
    {
        parent::__construct();
    }

      /*
     * @desc: GET services for /settings
     */
    public function get($request, $response) 
    {
        $match = array();

        $sessionService = new SessionService();
        $identityService = new IdentityService();

        if (isset($_SESSION['user'])) {
            $user = unserialize($_SESSION['user']);
            $available = $identityService->isUserLoggedIn($user->username);

            $user = $identityService->getUserSession();
            
            if ($available) {
                header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
    	        include("page/settings.php");
        	    exit();
            } else {
                header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
                include("page/login.php");
                exit();
            }
        } else {
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("page/login.php");
            exit();
        }
    }

     /*
     * @desc: PUT services for /settings
     */
    public function put($request, $response)
    {
        $match = array();
        $identityService = new IdentityService();
        $user = $identityService->getUserSession();
        $rawData = explode("&", $request['data']);
        foreach($rawData as $pair) {
            $pair = explode("=", $pair);
            $data[$pair[0]] = $pair[1];
        }

        if (preg_match('/^\/settings\/update\/?$/', $request['uri'], $match)) { // @url: /settings/update

            if ($data['setting'] === "soundNotification") {
                $user->settings->setSoundNotification($data['value']);
            }
            $settingsRepo = new SettingsRepo(true);
            $response['data'] = $settingsRepo->updateSettings($user);
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
        $request = array();
        $request['uri'] = $_SERVER['REQUEST_URI'];
        $request['headers'] = getallheaders();
        $response = array();
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $response = $this->get($request, $response);
        } else if ($_SERVER['REQUEST_METHOD'] ===  'PUT'){
            $request['data'] = file_get_contents("php://input");
            $response = $this->put($request, $response);
        } else {          
            $response['status'] = "-1";
            $response['statusMsg'] = "not a valid service";
            http_response_code(404);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
        }
        
        echo $response;
    }
}