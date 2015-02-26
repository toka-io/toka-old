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
        $component = parent::parseRequest($_SERVER['REQUEST_URI']);
        $queryParams = $_SERVER['QUERY_STRING'];
        
        // For debugging only
        $response['component'] = $component;
        $response['queryParams'] = $queryParams;
        
        // Service and action handler
        if ($component->component === 'service' && $component->service === 'category' && $component->action === 'all') {
            
            $categoryService = new CategoryService();            
            $response = $categoryService->getAllCategories($response);
            
        } else if ($component->component === 'service' && $component->service === 'category' && $component->action === "chatrooms") {
            
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
        $component = parent::parseRequest($_SERVER['REQUEST_URI']);
        $queryParams = $_SERVER['QUERY_STRING'];
        
        // For debugging only
        $response['component'] = $component;
        $response['queryParams'] = $queryParams;

        // Service and action handler
        if ($component->component === 'nothing') {
            
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