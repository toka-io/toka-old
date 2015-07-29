<?php
// @controller
require_once('BaseController.php');

// @service
require_once(__DIR__ . '/../service/IdentityService.php');

// @model
require_once(__DIR__ . '/../model/UserModel.php');

class ProfileController extends BaseController
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
        
        if (preg_match('/^\/profile\/leefter\/?$/', $request['uri'], $match)) { // @url: /profile/leefter
            
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("page/profile/profile_leefter.php");
            exit();
            
        } else if (preg_match('/^\/profile\/bob620\/?$/', $request['uri'], $match)) { // @url: /profile/bob620
            
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("page/profile/profile_bob620.php");
            exit();
            
        } else if (preg_match('/^\/profile\/([a-zA-Z0-9_]{3,25})\/?$/', $request['uri'], $match)) { // @url: /profile/:username
            
            $username = $match[1];
            
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("page/profile/profile.php");
            exit();
            
        } else if (preg_match('/^\/profile\/([a-zA-Z0-9_]{3,25})\/settings\/?$/', $request['uri'], $match)) { // @url: /profile/:username/settings
            
            $username = $match[1];
            
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("page/profile/settings.php");
            
            exit();
            
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