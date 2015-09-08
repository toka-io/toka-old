<?php
require_once('BaseController.php');
require_once('service/CategoryService.php');

class HomeController extends BaseController
{
    function __construct() 
    {
        parent::__construct();
    }

    /*
     * @desc: GET services for /
     */
    public function get($request, $response) 
    {
        $match = array();
        
        if (preg_match('/^\/?$/', $request['uri'], $match)) { // @url: /
        
            $categoryService = new CategoryService();
            
            $request['data']['categoryName'] = "Popular";
            $response = $categoryService->getChatrooms($request, $response);
            
            $categoryName = "Popular";
            
            $chatrooms = array();
            foreach ($response['data'] as $chatroom) {
                $chatrooms[$chatroom->chatroomId] = $chatroom;
            }
            
            $categoryImages = $categoryService->getCategoryImages();
    
            // Return category listing page for popular category
            include("page/category/category.php");
            exit();
        
        } else if (preg_match('/^\/faq\/?$/', $request['uri'], $match)) { 
            
            // Return faq page
            include("page/faq.php");
            exit();
            
        } else if (preg_match('/^\/error\/?$/', $request['uri'], $match)) { 
            
            // Return 500 page
            parent::redirect500();
            
        } else {
            
            http_response_code(404);
            include("error/404.php");
            exit();
            
        }
        
    }
    
    public function request()
    {
        $request = array();
        $request['uri'] = $_SERVER['REQUEST_URI'];
        $request['headers'] = getallheaders();
        $response = array();
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
            $response = $this->get($request, $response);
        else {          
            $response['status'] = ResponseCode::NOT_FOUND;
            $response['message'] = "not a valid service";
            http_response_code(404);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
        }
        
        echo $response;
    }
}