<?php
// @controller
require_once('BaseController.php');

// @service
require_once('service/IdentityService.php');

// @model
require_once('model/UserModel.php');

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
        
        if (preg_match('/^\/profile\/leefter\/?$/', $request['uri'], $match)) { // @url: /profile/leefter

            $username = "Leefter";
            
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("page/profile/profile_leefter.php");
            exit();
            
        } else if (preg_match('/^\/profile\/bob620\/?$/', $request['uri'], $match)) { // @url: /profile/bob620

            $username = "Bob620";
            
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("page/profile/profile_bob620.php");
            exit();
            
        } else if (preg_match('/^\/profile\/([a-zA-Z0-9_]{3,25})\/?$/', $request['uri'], $match)) { // @url: /profile/:username
            
            $username = $match[1];
            
            $identityService = new IdentityService();
            $available = $identityService->isUsernameAvailable($username);
            
            if (!$available) {                
                header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
                include("page/profile/profile.php");
                exit();
            } else {
                http_response_code(404);
                header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
                include("error/404.php");
                exit();
            }
            
        } else {
            
            http_response_code(404);
            include("error/404.php");
            exit();
            
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
            $response['status'] = "-1";
            $response['statusMsg'] = "not a valid service";
            http_response_code(404);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
        }
        
        echo $response;
    }
>>>>>>> master
}