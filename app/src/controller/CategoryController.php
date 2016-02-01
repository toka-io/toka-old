<?php
require_once('service/CategoryService.php');

class CategoryController extends Controller
{
    public function get($request, $response) {
        $match = array();
        
        if (RequestMapping::map('category\/(.*)', $request['uri'], $match)) { // @url: /category/:categoryName
            
            $request['data']['categoryName'] = $match[1];
            $response = CategoryService::getChatrooms($request, $response);
            
            $categoryName = $response['categoryName'];
            
            $chatrooms = array();
            foreach ($response['data'] as $chatroom) {
                $chatrooms[$chatroom->chatroomId] = $chatroom;
            }
            
            $categoryImages = CategoryService::getCategoryImages();
            
            // Return category listing page for specific category
            include("page/category/category.php");
        } 
        else
            parent::redirect404();
    }
}