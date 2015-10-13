<?php
require_once('model/GuestModel.php');
require_once('model/UserModel.php');
require_once('service/ChatroomService.php');
require_once('service/IdentityService.php');

class SessionService
{
    public static function initialize() {        
        \Cloudinary::config($GLOBALS['config']['cloudinary']);
        
        session_start();
            
        if (IdentityService::isUserLoggedIn()) {
            $user = IdentityService::getUserSession();              
            
            $_SESSION['user'] = serialize($user);
        } else {
            $_SESSION['guest'] = serialize(new GuestModel());
        }   
    }
    
    public static function updatePageHistory() {
        // update previous page session
        if ($_SERVER['REQUEST_METHOD'] === 'GET'
                && strpos($_SERVER['REQUEST_URI'], '/login') === false
                && strpos($_SERVER['REQUEST_URI'], '/logout') === false
                && strpos($_SERVER['REQUEST_URI'], '/rs') === false)
                    $_SESSION['prev_page'] = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        if (!isset($_SESSION['prev_page'])) {
            $_SESSION['prev_page'] = $_SERVER['SERVER_NAME'];
        }
    }
}