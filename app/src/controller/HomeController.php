<?php
require_once('service/CategoryService.php');

class HomeController extends Controller
{
    public function get($request, $response) {
        $match = array();
        
        if (RequestMapping::map('faq', $request['uri'], $match)) { 
            include("page/faq.php");
        } 
        else if (RequestMapping::map('error', $request['uri'], $match)) {
            parent::redirect500();
        } 
        else if (RequestMapping::map('', $request['uri'], $match)) {
            $request['data']['categoryName'] = "Popular";
            $response = CategoryService::getChatrooms($request, $response);
            $categoryName = "Popular";
            
            $chatrooms = array();
            foreach ($response['data'] as $chatroom) {
                $chatrooms[$chatroom->chatroomId] = $chatroom;
            }
            $categoryImages = CategoryService::getCategoryImages();
    
            include("page/category/category.php");
        } 
        else
            parent::redirect404();
        
    }
}