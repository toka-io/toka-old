<?php
// @model
require_once('model/CategoryModel.php');
require_once('model/ChatroomModel.php');

// @repo
require_once('repo/CategoryRepo.php');
require_once('repo/ChatroomRepo.php');

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
        $categoryRepo = new CategoryRepo(false);
        
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
    
    public function getCategoryImages() 
    {
        $categoryImages = array(
                'Startups' => '/assets/images/category_icons/white/toka_startups-01.png',
                'Anime' => '/assets/images/category_icons/white/toka_anime-01.png',
                'Gaming' => '/assets/images/category_icons/white/toka_games-01.png',
                'Programming' => '/assets/images/category_icons/white/toka_programming-01.png',
                'Food' => '/assets/images/category_icons/white/toka_food_2-01.png',
                'Travel' => '/assets/images/category_icons/white/toka_travel-01.png',
                'Trending' => '/assets/images/category_icons/white/toka_trending_2-01.png',
                'Music' => '/assets/images/category_icons/white/toka_music-01.png',
                'Sports' => '/assets/images/category_icons/white/toka_sports-01.png',
                'Movies and TV' => '/assets/images/category_icons/white/toka_video_2-01.png',
                'News' => '/assets/images/category_icons/white/toka_news-01.png',
                'Health' => '/assets/images/category_icons/white/toka_health-01.png',
                'Other' => '/assets/images/category_icons/white/toka_popular-01.png',
        );
        
        return $categoryImages;
    }
    
    public function getCategoryNameFromUrl($url) 
    {
        if(preg_match("/\/([a-zA-Z ]+)$/", $url, $matches))
            return $matches[1];
        else
            return NULL;
    }
    
    /*
     * @note: 
     */
    public function getChatrooms($request, $response)
    {
        $category = new CategoryModel();
        
        if (isset($request['data']['categoryName']))
            $category->setCategoryName($request['data']['categoryName']);
        
        $chatroomRepo = new ChatroomRepo(false);
        
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
            $response['data'] = $data;
        }
    
        return $response;
    }
}