<?php
require_once('model/Model.php');
require_once('service/CategoryService.php');

class CategoryController extends BaseController
{
    function __construct() 
    {
        parent::__construct();
    }

    /*
     * @desc: GET services for /category
     */
    public function get($request, $response) 
    {
        $match = array();
        
        if (preg_match('/^\/category\/?$/', $request['uri'], $match)) { // @url: /category
        
            $categoryService = new CategoryService();            
            $response = $categoryService->getAllCategories($response);
            $categories = $response['data'];
            
            // Return category listing page
            include("page/category/category_all.php");
            exit();
        
        } else if (preg_match('/^\/category\/(.*)\/?$/', $request['uri'], $match)) { // @url: /category/:categoryName
            
            $categoryService = new CategoryService();
            
            $request['data']['categoryName'] = $match[1];
            $response = $categoryService->getChatrooms($request, $response);
            
            $categoryName = $response['categoryName'];
            
            $chatrooms = array();
            foreach ($response['data'] as $chatroom) {
                $chatrooms[$chatroom->chatroomId] = $chatroom;
            }
            
            $categoryImages = $categoryService->getCategoryImages();
            
            // Return category listing page for specific category
            include("page/category/category.php");
            exit();
            
        } else {
            parent::redirect404();
        }
    }
}