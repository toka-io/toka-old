<?php
require_once('BaseController.php');
require_once('service/ChatroomService.php');
require_once('service/TokadownService.php');

class ChatroomController extends BaseController
{
    function __construct() 
    {
        parent::__construct();
    }

     /*
     * @desc: GET services for /chatroom
     */
    public function get($request, $response) 
    {
        $match = array();
        
        if (preg_match('/^\/chatroom\/([a-zA-Z0-9-_]+)\/?[^\/]*/', $request['uri'], $match)) { // @url: /chatroom/:chatroomId
            
            $chatroomService = new ChatroomService();
            $identityService = new IdentityService();
            $settingsService = new SettingsService();
            
            $request['data']['chatroomId'] = $match[1];
            
            $response = $chatroomService->getChatroom($request, $response);
            $chatroom = $response['data'];
            
            $chatroom->chatroomId = $match[1];
            $chatroom->chatroomType = ChatroomModel::CHATROOM_TYPE_NORMAL;
            
            if (empty($chatroom->chatroomName)) {                
                $userExists = $identityService->userExists($chatroom->chatroomId);    
                
                if ($userExists) {        
                    $chatroom->chatroomName = "@" . $chatroom->chatroomId;
                    $chatroom->chatroomType = ChatroomModel::CHATROOM_TYPE_USER;
                } else {
                    $chatroom->chatroomName = "#" . $chatroom->chatroomId;
                    $chatroom->chatroomType = ChatroomModel::CHATROOM_TYPE_HASHTAG;
                }                
            }
            
            $settings = array();
            if ($identityService->isUserLoggedIn()) {
                $user =  unserialize($_SESSION['user']);;
                $identityService->updateRecentRooms($user->username, $chatroom);
                $settings = $settingsService->getUserSettingsByUsername($user->username);
                $_SESSION['user'] = serialize($identityService->getUserSession());                
            }            
            
            // Return category listing page for specific category
            include("page/chatroom/chatroom.php");
            exit();
            
        } else {
            parent::redirect404();            
        }
    }
    
    /*
     * @desc: POST services for /chatroom
     */
    public function post($request, $response)
    {
        $match = array();

        if (preg_match('/^\/chatroom\/create\/?$/', $request['uri'], $match)) { // @url: /chatroom/create
        
            $chatroomService = new ChatroomService();
            $response = $chatroomService->createChatroom($request, $response);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } else if (preg_match('/^\/chatroom\/([a-zA-Z0-9-_]+)\/mod\/?$/', $request['uri'], $match)) { // @url: /chatroom/:chatroomId/mod
        
            $chatroomService = new ChatroomService();
            $response = $chatroomService->modUser($request, $response);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } else if (preg_match('/^\/chatroom\/([a-zA-Z0-9-_]+)\/unmod\/?$/', $request['uri'], $match)) { // @url: /chatroom/:chatroomId/unmod
        
            $chatroomService = new ChatroomService();
            $response = $chatroomService->unmodUser($request, $response);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } else if (preg_match('/^\/chatroom\/([a-zA-Z0-9-_]+)\/update\/?$/', $request['uri'], $match)) { // @url: /chatroom/:chatroomId/update
        
            $chatroomService = new ChatroomService();
            $response = $chatroomService->updateChatroom($request, $response);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } else {
            
            $response['status'] = ResponseCode::NOT_FOUND;
            $response['message'] = "not a valid service";
            http_response_code(404);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
            
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
        else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request['data'] = $_POST;
            $response = $this->post($request, $response);
        }
        else {          
            $response['status'] = ResponseCode::NOT_FOUND;
            $response['message'] = "not a valid service";
            http_response_code(404);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
        }
        
        echo $response;
    }
}