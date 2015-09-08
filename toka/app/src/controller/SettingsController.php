<?php
require_once('BaseController.php');
require_once('model/UserModel.php');
require_once('service/IdentityService.php');
require_once('service/SettingsService.php');

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

        $identityService = new IdentityService();
        $settingsService = new SettingsService();

        if (isset($_SESSION['user'])) {
            $user = unserialize($_SESSION['user']);

            $userSettings = $settingsService->getUserSettingsByUsername($user->username);
            
            if ($identityService->isUserLoggedIn($user->username)) {
    	        include("page/settings.php");
        	    exit();
            } else {
                include("page/login.php");
                exit();
            }
        } else {
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

        if (preg_match('/^\/settings\/update\/?$/', $request['uri'], $match)) { // @url: /settings/update
            
            $settingsService = new SettingsService();
            $identityService = new IdentityService();
            
            $data = json_decode($request['data'], true);
            $user = $identityService->getUserSession();
            
            $settings = Model::mapToObject(new SettingsModel(), $data);

            $response['result'] = $settingsService->updateSettingByUser($user, $settings);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } else {
            
            $response['status'] = ResponseCode::NOT_FOUND;
            $response['message'] = "not a valid service";
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
        } else if ($_SERVER['REQUEST_METHOD'] ===  'PUT') {
            $request['data'] = file_get_contents('php://input');            
            $response = $this->put($request, $response);
        } else {          
            $response['status'] = ResponseCode::NOT_FOUND;
            $response['message'] = "not a valid service";
            http_response_code(404);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
        }
        
        echo $response;
    }
}