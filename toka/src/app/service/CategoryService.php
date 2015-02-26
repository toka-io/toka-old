<?php
// @model
require_once(__DIR__ . '/../model/CategoryModel.php');
require_once(__DIR__ . '/../model/ChatroomModel.php');

// @repo
require_once(__DIR__ . '/../repo/CategoryRepo.php');
require_once(__DIR__ . '/../repo/ChatroomRepo.php');

/*
 * @note: Should we check whether a user exists when making the request? Double check...
 */
class CategoryService
{
    function __construct()
    {
    }
    
    /*
     * @note: Should we validate if the category exists? Double check...
     */
    public function getAllCategories($response)
    {
        $categoryRepo = new CategoryRepo();
        
        $data = $categoryRepo->getAllCategories();
        
        if (!isset($data['error'])) {
            $response['status'] = "1";
            $response['statusMsg'] = "all categories retrieved";
            $response['data'] = $data;
        } else {
            $response['status'] = "0";
            $response['statusMsg'] = "all categories were not retrievable";
        }
        
        return $response;
    }
    
    /*
     * @note: 
     */
    public function getChatrooms($response)
    {
        $category = new CategoryModel();
        
        if (isset($_GET['categoryName']))
            $category->setCategoryName($_GET['categoryName']);
        
        $chatroomRepo = new ChatroomRepo();
        
        $data = array();
        if ($category->categoryName === "Popular")
            $data = $chatroomRepo->getChatroomsByPopularity($category);
        else
            $data = $chatroomRepo->getChatroomsByCategory($category);
    
        if (!isset($data['error'])) {
            $response['status'] = "1";
            $response['statusMsg'] = "all chatrooms retrieved for category " . $category->categoryName;
            $response['categoryName'] = $category->categoryName;
            $response['data'] = $data;            
        } else {
            $response['status'] = "0";
            $response['statusMsg'] = "chatrooms for " . $category->categoryName . " were not retrievable";
        }
    
        return $response;
    }
}