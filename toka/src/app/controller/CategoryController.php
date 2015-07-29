<?php
// @controller
require_once('BaseController.php');

// @service
require_once(__DIR__ . '/../service/CategoryService.php');

class CategoryController extends BaseController
{
    function __construct() 
    {
        parent::__construct();
    }

    /*
     * @desc: GET services for /category
     */
    public function get() 
    {
        $request = array();
        $request['uri'] = $_SERVER['REQUEST_URI'];
        $request['headers'] = getallheaders();
        $response = array();
        $match = array();
        
        if (preg_match('/^\/category\/?$/', $request['uri'], $match)) { // @url: /category
        
            $categoryService = new CategoryService();            
            $response = $categoryService->getAllCategories($response);
            $categories = $response['data'];
            
            // Return category listing page
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("page/category/category_all.php");
            exit();
        
        } else if (preg_match('/^\/category\/(.*)\/?$/', $request['uri'], $match)) { // @url: /category/:categoryName
            
            $categoryService = new CategoryService();
            
            $request['data']['categoryName'] = $match[1];
            $response = $categoryService->getChatrooms($request, $response);
            
            $categoryName = $response['categoryName'];
            
            $chatrooms = array();
            foreach ($response['data'] as $key => $mongoObj) {
                // Add a try and catch if for some reason the chatroom is missing fields, do not show
                $chatroom = new ChatroomModel();
                $chatroom->bindMongo($mongoObj);
                $chatrooms[$chatroom->chatroomID] = $chatroom;
            }
            
            $categoryImages = $categoryService->getCategoryImages();
            
            // Return category listing page for specific category
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("page/category/category.php");
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