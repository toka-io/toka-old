<?php
require_once('BaseController.php');
require_once(__DIR__ . '/../service/IdentityService.php');

/* NOTE: Make sure to add aliases to require? and also see if we need to make a global check for if status == 0 we shouldn't change it to success or do anything */

class IdentityController extends BaseController
{
    function __construct() 
    {
        parent::__construct();
    }

    /*
     * @desc: There are currently no GET services for this controller
     */
    public function get() 
    {
        // Request & Response
        $request = array();
        $response = array();
        
        // Requested service
        $component = parent::parseRequest($_SERVER['REQUEST_URI']);
        $queryParams = $_SERVER['QUERY_STRING'];
        
        // For debugging only
        $response['component'] = $component;
        $response['queryParams'] = $queryParams;
        
        $request['data'] = $_GET;

        if ($component->component === 'service' && $component->service === 'user' && $component->action === 'isEmailAvailable') {
        
            $identityService = new IdentityService();
            $response = $identityService->isEmailAvailable($request, $response);
        
        } else if ($component->component === 'service' && $component->service === 'user' && $component->action === 'isUsernameAvailable') {
        
            $identityService = new IdentityService();
            $response = $identityService->isUsernameAvailable($request, $response);
        
        } else if ($component->component === 'page' && $component->service === 'logout' && $component->action === NULL) {
        
            $identityService = new IdentityService();
            $response = $identityService->logout($request, $response);
        
        } else {
            
            $response['status'] = "-1";
            $response['statusMsg'] = "not a valid service";
            
        }
        
        parent::setContentType(BaseController::MIME_TYPE_APPLICATION_JSON);
        return json_encode($response);
    }
    
    public function post()
    {
        // Request & Response
        $request = array();
        $response = array();
        
        // Requested service
        $component = parent::parseRequest($_SERVER['REQUEST_URI']);
        $queryParams = $_SERVER['QUERY_STRING'];
        
        // For debugging only
        $response['component'] = $component;
        $response['queryParams'] = $queryParams;
        
        $request['data'] = $_POST;

        if ($component->component === 'page' && $component->service === 'login' && $component->action === NULL) {
        
            $identityService = new IdentityService();
            $response = $identityService->login($request, $response);
        
        } else if ($component->component === 'page' && $component->service === 'signup' && $component->action === NULL) {
            
            $identityService = new IdentityService();
            $response = $identityService->createUser($request, $response);
            
        } else {
            
            $response['status'] = "-1";
            $response['statusMsg'] = "not a valid service and/or action";
            
        }
        
        parent::setContentType(BaseController::MIME_TYPE_APPLICATION_JSON);
        return json_encode($response);
    }
}