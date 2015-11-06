<?php
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
            
            $request['data']['categoryName'] = "Popular";
            $response = CategoryService::getChatrooms($request, $response);
            
            $categoryName = "Popular";
            
            $chatrooms = array();
            foreach ($response['data'] as $chatroom) {
                $chatrooms[$chatroom->chatroomId] = $chatroom;
            }
            
            $categoryImages = CategoryService::getCategoryImages();
    
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
}