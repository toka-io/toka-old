<?php
// @controller
require_once('BaseController.php');

// @service
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
    public function get() 
    {
        $request = array();
        $request['uri'] = $_SERVER['REQUEST_URI'];
        $request['headers'] = getallheaders();
        $response = array();
        $match = array();
        
        if (preg_match('/^\/?$/', $request['uri'], $match)) { // @url: /
        
            $categoryService = new CategoryService();
            
            $request['data']['categoryName'] = "Popular";
            $response = $categoryService->getChatrooms($request, $response);
            
            $categoryName = "Popular";
            
            $chatrooms = array();
            foreach ($response['data'] as $key => $mongoObj) {
                // Add a try and catch if for some reason the chatroom is missing fields, do not show
                $chatroom = new ChatroomModel();
                $chatroom->bindMongo($mongoObj);
                $chatrooms[$chatroom->chatroomId] = $chatroom;
            }
            
            $categoryImages = $categoryService->getCategoryImages();
    
            // Return category listing page for popular category
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("page/category/category.php");
            exit();
        
        } else if (preg_match('/^\/faq\/?$/', $request['uri'], $match)) { 
            
            // Return faq page
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("page/faq.php");
            exit();
            
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