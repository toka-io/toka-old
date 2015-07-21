<?php
// @controller
require_once('BaseController.php');

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
        
        if (preg_match('/^\/profile\/([a-zA-Z0-9_]{3,25})\/?$/', $request['uri'], $match)) { // @url: /category/:categoryName
            
            $username = $match[1];
            
            // Return category listing page for specific category
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("/../public/view/page/profile/profile.php");
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