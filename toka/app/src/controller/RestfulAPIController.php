<?php
require_once('BaseController.php');
require_once('service/IdentityService.php');

class RestfulAPIController extends BaseController
{
    function __construct() 
    {
        parent::__construct();
    }
    
    /*
     * @desc: POST services for /rs-api/login
     */
    public function post($request, $response)
    {
        $match = array();
        
        if (preg_match('/^\/rs-api\/login\/?$/', $request['uri'], $match)) {
            
            // Log in user
            $identityService = new IdentityService();
            $response = $identityService->login($_POST, $response);
            
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
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
            $response = $this->post($request, $response);
        else {
            $response['status'] = "-1";
            $response['statusMsg'] = "not a valid service";
            http_response_code(404);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
            $response = json_encode($response);
        }
        
        echo $response;
    }
}