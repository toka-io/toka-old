<?php
// @model
require_once(__DIR__ . '/../model/GuestModel.php');
require_once(__DIR__ . '/../model/UserModel.php');

// @service
require_once(__DIR__ . '/../service/IdentityService.php');

class SessionService
{
    
    function __construct()
    {
    }
    
    function initialize() {
        $identityService = new IdentityService();
        
        session_start();
        
        // Update previous page session
        if ($_SERVER['REQUEST_URI'] != "/login" && $_SERVER['REQUEST_URI'] != "/logout")
            $_SESSION['prev_page'] = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        
        // Set previous page to home page if it is null
        if (!isset($_SESSION['prev_page']))
            $_SESSION['prev_page'] = $_SERVER['SERVER_NAME'];
            
        if ($identityService->isUserLoggedIn()) {
            $user = $identityService->getUserSession();
            
            // move this logic to getUserSession()!!
            $userChatrooms = $identityService->getChatroomsByOwner($user); // Get chatrooms owned by user
            $user->hasMaxChatrooms = $identityService->hasMaxChatrooms($user); // Can user create more chatrooms?
            $user->hasChatrooms = false; // Does user have a chatroom?
            $user->homeChatroom = new ChatroomModel();
            
            if (!empty($userChatrooms)) {
                $mongoObj = $userChatrooms["0"];
                $user->homeChatroom->bindMongo($mongoObj);
                $user->hasChatrooms = true;
            }
            
            $_SESSION['user'] = serialize($user);
        } else {
            $_SESSION['guest'] = serialize(new GuestModel());
        }
            
    }
}