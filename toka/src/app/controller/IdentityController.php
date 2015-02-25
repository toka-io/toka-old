<?php
require_once('BaseController.php');
require_once('../../service/IdentityService.php');

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
        // Response
        $response = array();
        
        // Requested service
        $service = parent::parseService($_SERVER['REQUEST_URI']);
        $queryParams = $_SERVER['QUERY_STRING'];
        
        // For debugging only
        $response['service'] = $service;
        $response['queryParams'] = $queryParams;
        
        // Service and action handler
        if ($service->service === "logout" && $service->action === NULL) {
        
            $identityService = new IdentityService();
            $response = $identityService->logout($response);
        
            $response['getData'] = $_GET;
        
        } else {
            
            $response['status'] = "0";
            $response['statusMsg'] = "not a valid service";
            
        }
        
        parent::setContentType(BaseController::MIME_TYPE_APPLICATION_JSON);
        return json_encode($response);
    }
    
    public function post()
    {
        // Response
        $response = array();
        
        // Requested service
        $service = parent::parseService($_SERVER['REQUEST_URI']);
        $queryParams = $_SERVER['QUERY_STRING'];
        
        // For debugging only
        $response['service'] = $service;
        $response['queryParams'] = $queryParams;

        if ($service->service === "login" && $service->action === NULL) {
        
            $identityService = new IdentityService();
            $response = $identityService->login($response);
        
            $response['postData'] = $_POST;
        
        } else if ($service->service === "logout" && $service->action === NULL) {
        
            $identityService = new IdentityService();
            $response = $identityService->logout($response);
        
            $response['postData'] = $_POST;
        
        } else if ($service->service === "signup" && $service->action === NULL) {
            
            $identityService = new IdentityService();
            $response = $identityService->createUser($response);
            
            $response['postData'] = $_POST;
            
        } else if ($service->service === "user" && $service->action === "deactivate") {
        
            $identityService = new IdentityService();
            $response = $identityService->deactivateUser($response);
        
            $response['postData'] = $_POST;
        
        } else {
            
            $response['status'] = "0";
            $response['statusMsg'] = "not a valid service and/or action";
            
        }
        
        parent::setContentType(BaseController::MIME_TYPE_APPLICATION_JSON);
        return json_encode($response);
    }
}