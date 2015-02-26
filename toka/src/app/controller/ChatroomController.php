<?php
require_once('BaseController.php');
require_once('../../service/ChatroomService.php');

/* NOTE: Make sure to add aliases to require? and also see if we need to make a global check for if status == 0 we shouldn't change it to success or do anything */

class ChatroomController extends BaseController
{
    function __construct() 
    {
        parent::__construct();
    }

    /*
     * @desc: There are currently no GET services for this controller
     */
    public function get() 
    {
        // Response
        $response = array();
        
        // Requested service
        $component = parent::parseRequest($_SERVER['REQUEST_URI']);
        $queryParams = $_SERVER['QUERY_STRING'];
        
        // For debugging only
        $response['component'] = $component;
        $response['queryParams'] = $queryParams;
        
        // Service and action handler
        if ($component->component === "page" && $component->service === "chatroom" && !empty($component->action)) {
            
            $chatroomService = new ChatroomService();
            $response = $chatroomService->getChatroom($response);
        
            $response['getData'] = $_GET;
            
        } else {
            
            $response['status'] = "0";
            $response['statusMsg'] = "not a valid service";
            
        }
        
        parent::setContentType(BaseController::MIME_TYPE_APPLICATION_JSON);
        return json_encode($response);
    }
    
    public function post()
    {
        // Response
        $response = array();
        
        // Requested service
        $component = parent::parseRequest($_SERVER['REQUEST_URI']);
        $queryParams = $_SERVER['QUERY_STRING'];
        
        // For debugging only
        $response['component'] = $component;
        $response['queryParams'] = $queryParams;

        if ($component->component === 'service' && $component->service === 'chatroom' && $component->action === 'create') {
        
            $chatroomService = new ChatroomService();
            $response = $chatroomService->createChatroom($response);
        
            $response['postData'] = $_POST;
        
        } else if ($component->component === 'service' && $component->service === 'chatroom' && $component->action === 'enter') {
        
            $chatroomService = new ChatroomService();
            $response = $chatroomService->enterChatroom($response);
        
            $response['postData'] = $_POST;
        
        } else if ($component->component === 'service' && $component->service === 'chatroom' && $component->action === 'leave') {
        
            $chatroomService = new ChatroomService();
            $response = $chatroomService->leaveChatroom($response);
        
            $response['postData'] = $_POST;
        
        } else if ($component->component === 'service' && $component->service === 'chatroom' && $component->action === 'mod') {
        
            $chatroomService = new ChatroomService();
            $response = $chatroomService->modUser($response);
        
            $response['postData'] = $_POST;
        
        } else if ($component->component === 'service' && $component->service === 'chatroom' && $component->action === 'unmod') {
        
            $chatroomService = new ChatroomService();
            $response = $chatroomService->unmodUser($response);
        
            $response['postData'] = $_POST;
        
        } else if ($component->component === 'service' && $component->service === 'chatroom' && $component->action === 'update') {
        
            $chatroomService = new ChatroomService();
            $response = $chatroomService->updateChatroom($response);
        
            $response['postData'] = $_POST;
        
        } else {
            
            $response['status'] = '0';
            $response['statusMsg'] = 'not a valid service and/or action';
            
        }
        
        parent::setContentType(BaseController::MIME_TYPE_APPLICATION_JSON);
        return json_encode($response);
    }
}