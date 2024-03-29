<?php
require_once('service/ChatroomService.php');
require_once('service/TokadownService.php');

class ChatroomController extends Controller
{
    public function get($request, $response) {
        $match = array();
        
        if (RequestMapping::map('chatroom\/([a-zA-Z0-9-_]+)', $request['uri'], $match)) { // @url: /chatroom/:chatroomId
            
            $request['data']['chatroomId'] = $match[1];
            
            $response = ChatroomService::getChatroom($request, $response);
            $chatroom = $response['data'];
            
            $chatroom->chatroomId = $match[1];
            $chatroom->chatroomType = Chatroom::CHATROOM_TYPE_NORMAL;
            
            if (empty($chatroom->chatroomName)) {                
                $userExists = IdentityService::userExists($chatroom->chatroomId);    
                
                if ($userExists) {        
                    $chatroom->chatroomName = "@" . $chatroom->chatroomId;
                    $chatroom->chatroomType = Chatroom::CHATROOM_TYPE_USER;
                } else {
                    $chatroom->chatroomName = "#" . $chatroom->chatroomId;
                    $chatroom->chatroomType = Chatroom::CHATROOM_TYPE_HASHTAG;
                }                
            }
            
            $settings = array();
            if (IdentityService::isUserLoggedIn()) {
                $user =  unserialize($_SESSION['user']);;
                IdentityService::updateRecentRooms($user->username, $chatroom);
                $settings = SettingsService::getUserSettingsByUsername($user->username);
                $_SESSION['user'] = serialize(IdentityService::getUserSession());                
            }            
            
            $metadataCache = MetadataService::getMetadataArchive(100);
            
            // Return category listing page for specific category
            include("page/chatroom/chatroom.php");
        } 
        else
            parent::redirect404();
    }
    
    public function post($request, $response) {
        $match = array();

        if (RequestMapping::map('chatroom\/create', $request['uri'], $match)) { // @url: /chatroom/create

            $request['data'] = $_POST;
            $response = ChatroomService::createChatroom($request, $response);
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } 
        else if (RequestMapping::map('chatroom\/([a-zA-Z0-9-_]+)\/mod', $request['uri'], $match)) { // @url: /chatroom/:chatroomId/mod
        
            $request['data'] = $_POST;
            $response = ChatroomService::modUser($request, $response);
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } 
        else if (RequestMapping::map('chatroom\/([a-zA-Z0-9-_]+)\/unmod', $request['uri'], $match)) { // @url: /chatroom/:chatroomId/unmod
        
            $request['data'] = $_POST;
            $response = ChatroomService::unmodUser($request, $response);
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } 
        else if (RequestMapping::map('chatroom\/([a-zA-Z0-9-_]+)\/update', $request['uri'], $match)) { // @url: /chatroom/:chatroomId/update
        
            $request['data'] = $_POST;
            $response = ChatroomService::updateChatroom($request, $response);
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } 
        else
            parent::redirectRS404();
    }
}