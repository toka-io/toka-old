<?php
// @controller
require_once('BaseController.php');

// @service
require_once('../../service/CategoryService.php');

/* NOTE: Make sure to add aliases to require? and also see if we need to make a global check for if status == 0 we shouldn't change it to success or do anything */

class CategoryController extends BaseController
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
        if ($service->service === "category" && $service->action === 'all') {
            
            $categoryService = new CategoryService();
            
            $response = $categoryService->getAllCategories($response);
            
        } else if ($service->service === "category" && $service->action === "chatrooms") {
            
            $categoryService = new CategoryService();
            
            $response = $categoryService->getChatrooms($response);
            
        } else {            
            $response['status'] = "0";
            $response['statusMsg'] = "not a valid service";
        }
        
        parent::setContentType(BaseController::MIME_TYPE_APPLICATION_JSON);
        return json_encode($response);
    }
    
    /*
     * @desc: There are currently no POST services for this controller
     */
    public function post()
    {
        // Response
        $response = array();
        
        // Requested service
        $service = parent::parseService($_SERVER['REQUEST_URI']);
        $queryParams = $_SERVER['QUERY_STRING'];
        
        // Parse service url
        $service = parent::parseService($service);
        
        // For debugging only
        $response['service'] = $service;
        $response['queryParams'] = $queryParams;

        // Service and action handler
        if ($service->service === 'nothing') {
            
            $response['status'] = "1";
            $response['statusMsg'] = "ok";
            
        } else {
            
            $response['status'] = "0";
            $response['statusMsg'] = "not a valid service";
            
        }
        
        parent::setContentType(BaseController::MIME_TYPE_APPLICATION_JSON);
        return json_encode($response);
    }
}