<?php
require_once('BaseController.php');
require_once(__DIR__ . '/../service/IdentityService.php');

/* NOTE: Make sure to add aliases to require? and also see if we need to make a global check for if status == 0 we shouldn't change it to success or do anything */

class IdentityController extends BaseController
{
    const SERVICE_URL = 'service\/user';
    
    function __construct() 
    {
        parent::__construct();
    }
    
    /*
     * @desc: GET operations for /service/user
     */
    public function get() 
    {  
        $request = $_SERVER['REQUEST_URI'];
        $queryParams = $_SERVER['QUERY_STRING']; 
        $headers = getallheaders();    

        // @url: /service/user/:username/isUsernameAvailable
        if (preg_match('/'.IdentityController::SERVICE_URL.'\/([a-zA-Z0-9_]{3,25})\/isUsernameAvailable\/?/', $request, $match)) {
            
            $identityService = new IdentityService();
            $username = $match[1];
            $response = $identityService->isUsernameAvailable($username);
            
            parent::setContentType(BaseController::MIME_TYPE_TEXT_HTML);
            return json_encode($response);
            
        } else {
            
            $response = array();
            $response['status'] = "-1";
            $response['statusMsg'] = "not a valid service";
            
            parent::setContentType(BaseController::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
            
        }
    }
    
    /*
     * @desc: POST operations for /service/user
     */
    public function post()
    {
        // Request & Response
        $request = array();
        $response = array();
        
        // Requested service
        $component = parent::parseRequest($_SERVER['REQUEST_URI']);
        $queryParams = $_SERVER['QUERY_STRING'];
        
        $request['data'] = $_POST;

        if ($component->component === 'page' && $component->service === 'login' && $component->action === NULL) {
        
            $identityService = new IdentityService();
            $response = $identityService->login($request['data'], $response);

            parent::setContentType(BaseController::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } else if ($component->component === 'page' && $component->service === 'signup' && $component->action === NULL) {
            
            $identityService = new IdentityService();
            $response = $identityService->createUser($request, $response);
            
            parent::setContentType(BaseController::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
            
        } else {
            
            $response['status'] = "-1";
            $response['statusMsg'] = "not a valid service and/or action";
            
            parent::setContentType(BaseController::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
            
        }        
    }
    
    public function request()
    {
        parent::request();
    }
}