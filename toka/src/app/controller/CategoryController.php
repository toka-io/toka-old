<?php
// @controller
require_once('BaseController.php');

// @service
require_once(__DIR__ . '/../service/CategoryService.php');

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
        $request = $_SERVER['REQUEST_URI'];
        $headers = getallheaders();
        $response = array();
        
        if (preg_match('/category\/?/', $request, $match)) { // @url: /login
        
            $categoryService = new CategoryService();            
            $response = $categoryService->getAllCategories($request, $response);
            $categories = $response['data'];
            
            // Return category listing page
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("/../public/view/page/category/category_all.php");
            exit();
        
        } else if ($component->component === 'service' && $component->service === 'category' && $component->action === "chatrooms") {
            
            $categoryService = new CategoryService();            
            $response = $categoryService->getChatrooms($request, $response);
            
        } else {            
            $response['status'] = "-1";
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

        // Service and action handler
        if ($component->component === 'nothing') {
            
            $response['status'] = "1";
            $response['statusMsg'] = "ok";
            
        } else {
            
            $response['status'] = "-1";
            $response['statusMsg'] = "not a valid service";
            
        }
        
        parent::setContentType(BaseController::MIME_TYPE_APPLICATION_JSON);
        return json_encode($response);
    }
    
    public function request()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
            $response = $this->get();
        else if ($_SERVER['REQUEST_METHOD'] === 'POST')
            $response = $this->post();
        else {
            $request = $_SERVER['REQUEST_URI'];
            $headers = getallheaders();
            $response = array();
            
            $response['status'] = "-1";
            $response['statusMsg'] = "not a valid service";
            http_response_code(404);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
        }
        
        echo $response;
    }
}