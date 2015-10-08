<?php
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

        if (isset($_SESSION['user'])) {
            $user = unserialize($_SESSION['user']);

            $userSettings = SettingsService::getUserSettingsByUsername($user->username);
            
            if (IdentityService::isUserLoggedIn($user->username)) {
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
            
            $data = json_decode($request['data'], true);
            $user = IdentityService::getUserSession();

            $response['result'] = SettingsService::updateSettingByUser($user, $data);
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } else {
            
            $response['status'] = ResponseCode::NOT_FOUND;
            $response['message'] = "not a valid service";
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
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
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
        }
        
        echo $response;
    }
}