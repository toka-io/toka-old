<?php
// @controller
require_once('BaseController.php');

// @service
require_once('service/ChatroomService.php');
require_once('service/TokadownService.php');

/* NOTE: Make sure to add aliases to require? and also see if we need to make a global check for if status == 0 we shouldn't change it to success or do anything */

class ChatroomController extends BaseController
{
    function __construct() 
    {
        parent::__construct();
    }

     /*
     * @desc: GET services for /chatroom
     */
    public function get() 
    {
        $request = array();
        $request['uri'] = $_SERVER['REQUEST_URI'];
        $request['headers'] = getallheaders();
        $response = array();
        $match = array();
        
        if (preg_match('/^\/chatroom\/([a-zA-Z0-9-_]+)\/?$/', $request['uri'], $match)) { // @url: /chatroom/:chatroomId
            
            $chatroomService = new ChatroomService();
            $identityService = new IdentityService();
            
            $request['data']['chatroomID'] = $match[1];
            $response = $chatroomService->getChatroom($request, $response);
            
            $mongoObj = $response['data'];
            
            $chatroom = new ChatroomModel();
            $chatroom->bindMongo($mongoObj);
            
            if (empty($chatroom->chatroomName)) {
                $chatroom->chatroomID = strtolower($request['data']['chatroomID']);
                
                $tokaUser = new UserModel();
                $tokaUser->setUsername($chatroom->chatroomID);
                $userExists = $identityService->checkUserExists($tokaUser);    
                
                if ($userExists) {        
                    $chatroom->chatroomName = "@" . $chatroom->chatroomID;
                } else {
                    $chatroom->chatroomName = "#" . $chatroom->chatroomID;
                }
            }
            
            // Return category listing page for specific category
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("page/chatroom/chatroom.php");
            exit();
            
        } else {
            
            $response['status'] = "-1";
            $response['statusMsg'] = "not a valid service";
            http_response_code(404);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
            
        }
    }
    
    /*
     * @desc: POST services for /chatroom
     */
    public function post()
    {
        $request = array();
        $request['uri'] = $_SERVER['REQUEST_URI'];
        $request['headers'] = getallheaders();
        $request['data'] = $_POST;
        $response = array();
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
            
            $response['status'] = "-1";
            $response['statusMsg'] = "not a valid service";
            http_response_code(404);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
            
        }
    }
    
    public function request()
    {
        $response = array();
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
            $response = $this->get();
        else if ($_SERVER['REQUEST_METHOD'] === 'POST')
            $response = $this->post();
        else {          
            $response['status'] = "-1";
            $response['statusMsg'] = "not a valid service";
            http_response_code(404);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
        }
        
        echo $response;
    }
}