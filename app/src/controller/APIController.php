<?php
require_once('service/IdentityService.php');
require_once('service/MetadataService.php');
require_once('service/SearchService.php');
require_once('service/CategoryService.php');
require_once('service/ChatroomService.php');
require_once('service/SettingsService.php');

class APIController extends BaseController
{
    function __construct() 
    {
        parent::__construct();
    }
    
    public function get($request, $response) 
    {
        $match = array();

        if (preg_match('/^\/api\/chatroom\/search\/?[^\/]*/', $request['uri'], $match)) {
        
            if (isset($_GET['c']))
                $name = $_GET['c'];
            else
                return json_encode($response);
        
            $response['status'] = ResponseCode::SUCCESS;
            $response['result'] = SearchService::searchChatroomsByName($name);
        
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } else if (preg_match('/^\/api\/user\/([a-zA-Z0-9_]{3,25})\/available\/?$/', $request['uri'], $match)) {
        
            $username = $match[1];        
            $response['available'] = IdentityService::isUsernameAvailable($username);
        
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } else if (preg_match('/^\/api\/user\/search\/?[^\/]*/', $request['uri'], $match)) {
        
            if (isset($_GET['u']))
                $username = $_GET['u'];
            else
                return json_encode($response);
        
            $response['status'] = ResponseCode::SUCCESS;
            $response['result'] = SearchService::searchUsersByUsername($username);
        
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } else if (preg_match('/^\/api\/web\/meta\/?$/', $request['uri'], $match)) {
            
            $result = MetadataService::getMetadataArchive(100);
            
            $response['status'] = ResponseCode::SUCCESS;
            $response['result'] = $result; 
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);

        } else {            
            parent::redirectRS404();            
        }              
    }    
    
    public function post($request, $response)
    {
        $match = array();

        if (preg_match('/^\/api\/categories\/?$/', $request['uri'], $match)) {

            // Retrive all Categories
            $response = CategoryService::getAllCategories($response);

            return json_encode($response);

        } else if (preg_match('/^\/api\/category\/?$/', $request['uri'], $match)) {
            // categoryName: Name

            // Retrive all Chatrooms of a Category
            $response = CategoryService::getChatrooms($request, $response);

            return json_encode($response);

        } else if (preg_match('/^\/api\/chatroom\/?$/', $request['uri'], $match)) {
            // chatroomId: Id

            // Retrive the info of a Chatroom
            $response = ChatroomService::getChatroom($request, $response);

            return json_encode($response);

        } else if (preg_match('/^\/api\/login\/?$/', $request['uri'], $match)) {
            // password: password
            // username: username
            
            // Log in user
            $response = IdentityService::login($_POST, $response);
            
            return json_encode($response);
            
        } else if (preg_match('/^\/api\/history\/?$/', $request['uri'], $match)) {

            $user = IdentityService::getUserSession();

            // Retrive user history
            $response = IdentityService::getRecentRoomsByUsername($user->username);

            return json_encode($response);

        } else if (preg_match('/^\/api\/settings\/?$/', $request['uri'], $match)) {

            $user = IdentityService::getUserSession();

            // Retrive user settings
            SettingsService::getUserSettingsByUsername($user->username);

            return json_encode($response);

        } else if (preg_match('/^\/api\/user\/?$/', $request['uri'], $match)) {

        } else if (preg_match('/^\/api\/web\/meta\/fetch\/?$/', $request['uri'], $match)) {
            try {
                $data = json_decode($request['data'], true);            
                $result = MetadataService::getMetadataByUrl($data);
                
                $response['status'] = ResponseCode::SUCCESS;
                $response['result'] = $result; 
                header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
                return json_encode($response);
            } catch (Exception $e) {
                return parent::get500Response("Could not resolve url!");
            }

        } else {            
            parent::redirectRS404();            
        }        
    }
}