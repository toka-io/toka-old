<?php
require_once('model/Category.php');
require_once('model/Chatroom.php');
require_once('repo/CategoryRepo.php');
require_once('repo/ChatroomRepo.php');

/*
 * @note: Should we check whether a user exists when making the request? Double check...
 */
class CategoryService
{
    public static function getAllCategories($response) {
        $categoryRepo = new CategoryRepo(false);
        
        $data = $categoryRepo->getAllCategories();
        
        if (!isset($data['error'])) {
            $response['status'] = ResponseCode::SUCCESS;
            $response['message'] = "all categories retrieved";
            $response['data'] = $data;
        } else {
            $response['status'] = ResponseCode::INTERNAL_SERVER_ERROR;
            $response['message'] = "all categories were not retrievable";
        }
        
        return $response;
    }
    
    public static function getCategoryImages() {
        $categoryImages = array(
                'Startups' => '/assets/images/category-icons/white/toka_startups-01.png',
                'Anime' => '/assets/images/category-icons/white/toka_anime-01.png',
                'Gaming' => '/assets/images/category-icons/white/toka_games-01.png',
                'Programming' => '/assets/images/category-icons/white/toka_programming-01.png',
                'Food' => '/assets/images/category-icons/white/toka_food_2-01.png',
                'Travel' => '/assets/images/category-icons/white/toka_travel-01.png',
                'Trending' => '/assets/images/category-icons/white/toka_trending_2-01.png',
                'Music' => '/assets/images/category-icons/white/toka_music-01.png',
                'Sports' => '/assets/images/category-icons/white/toka_sports-01.png',
                'Movies and TV' => '/assets/images/category-icons/white/toka_video_2-01.png',
                'News' => '/assets/images/category-icons/white/toka_news-01.png',
                'Health' => '/assets/images/category-icons/white/toka_health-01.png',
                'Other' => '/assets/images/category-icons/white/toka_popular-01.png',
        );
        
        return $categoryImages;
    }
    
    public static function getCategoryNameFromUrl($url) {
        if(preg_match("/\/([a-zA-Z ]+)$/", $url, $matches))
            return $matches[1];
        else
            return NULL;
    }
    
    public static function getChatrooms($request, $response) {
        $category = new Category();
        
        if (isset($request['data']['categoryName']))
            $category->setCategoryName($request['data']['categoryName']);
        
        $chatroomRepo = new ChatroomRepo(false);
        
        $data = array();
        if ($category->categoryName === "Popular")
            $data = $chatroomRepo->getChatroomsByPopularity($category);
        else
            $data = $chatroomRepo->getChatroomsByCategory($category);
    
        if (!isset($data['error'])) {
            $response['status'] = ResponseCode::SUCCESS;
            $response['message'] = "all chatrooms retrieved for category " . $category->categoryName;
            $response['categoryName'] = $category->categoryName;
            $response['data'] = $data;            
        } else {
            $response['status'] = ResponseCode::INTERNAL_SERVER_ERROR;
            $response['message'] = "chatrooms for " . $category->categoryName . " were not retrievable";
            $response['data'] = $data;
        }
    
        return $response;
    }
}