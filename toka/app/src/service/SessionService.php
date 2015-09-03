<?php
// @model
require_once('model/GuestModel.php');
require_once('model/UserModel.php');

// @service
require_once('service/IdentityService.php');

class SessionService
{
    
    function __construct()
    {
    }
    
    function initialize() {        
        \Cloudinary::config($GLOBALS['config']['cloudinary']);
        
        $identityService = new IdentityService();
        
        session_start();
        
        // update previous page session
        if ($_SERVER['REQUEST_URI'] != "/login" && $_SERVER['REQUEST_URI'] != "/logout")
            $_SESSION['prev_page'] = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        
        // set previous page to home page if it is null
        if (!isset($_SESSION['prev_page']))
            $_SESSION['prev_page'] = $_SERVER['SERVER_NAME'];
            
        if ($identityService->isUserLoggedIn()) {
            $user = $identityService->getUserSession();
            
            // move this logic to getUserSession()!!
            $user->chatrooms = $identityService->getChatroomsByOwner($user); // Get chatrooms owned by user
            $user->hasMaxChatrooms = $identityService->hasMaxChatrooms($user); // Can user create more chatrooms?
            $user->hasChatrooms = false; // Does user have a chatroom?            
            
            if (!empty($user->chatrooms)) {
                $user->homeChatroom = Model::mapToObject(new ChatroomModel(), $user->chatrooms["0"]);
                $user->hasChatrooms = true;
            }
            
            $_SESSION['user'] = serialize($user);
        } else {
            $_SESSION['guest'] = serialize(new GuestModel());
        }
            
    }
}