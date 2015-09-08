<?php
require_once('BaseController.php');
require_once('model/UserModel.php');
require_once('service/IdentityService.php');

class ProfileController extends BaseController
{
    function __construct() 
    {
        parent::__construct();
    }

      /*
     * @desc: GET services for /profile
     */
    public function get($request, $response) 
    {
        $match = array();
        
        if (preg_match('/^\/profile\/([a-zA-Z0-9_]{3,25})\/?$/', $request['uri'], $match)) { // @url: /profile/:username
            
            $username = $match[1];
            
            $identityService = new IdentityService();
            $available = $identityService->isUsernameAvailable($username);
            
            if (!$available) {
                include("page/profile/profile.php");
                exit();
            } else {
                parent::redirect404();
            }
            
        } else {            
            parent::redirect404();
        }
    }
    
    public function request()
    {
        $request = array();
        $request['uri'] = $_SERVER['REQUEST_URI'];
        $request['headers'] = getallheaders();
        $response = array();
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
            $response = $this->get($request, $response);
        else {          
            $response['status'] = ResponseCode::NOT_FOUND;
            $response['message'] = "not a valid service";
            http_response_code(404);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
        }
        
        echo $response;
    }
}