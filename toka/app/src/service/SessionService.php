<?php
require_once('model/GuestModel.php');
require_once('model/UserModel.php');
require_once('service/ChatroomService.php');
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
        if ($_SERVER['REQUEST_METHOD'] === 'GET'
                && strpos($_SERVER['REQUEST_URI'], '/login') === false 
                && strpos($_SERVER['REQUEST_URI'], '/logout') === false 
                && strpos($_SERVER['REQUEST_URI'], '/rs') === false)
            $_SESSION['prev_page'] = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        
        // set previous page to home page if it is null
        if (!isset($_SESSION['prev_page']))
            $_SESSION['prev_page'] = $_SERVER['SERVER_NAME'];
            
        if ($identityService->isUserLoggedIn()) {
            $user = $identityService->getUserSession();              
            
            $_SESSION['user'] = serialize($user);
        } else {
            $_SESSION['guest'] = serialize(new GuestModel());
        }
            
    }
}