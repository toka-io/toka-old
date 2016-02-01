<?php
require_once ('model/Guest.php');
require_once ('model/User.php');
require_once ('service/ChatroomService.php');
require_once ('service/IdentityService.php');

class SessionService {
    
    public static function initialize() {
        \Cloudinary::config ( $GLOBALS ['config'] ['cloudinary'] );
        
        session_start ();
        
        if (IdentityService::isUserLoggedIn ()) {
            $user = IdentityService::getUserSession ();
            
            $_SESSION ['user'] = serialize ( $user );
        } else {
            $_SESSION ['guest'] = serialize ( new Guest () );
        }
    }
    
    public static function updatePageHistory() {
        $_SESSION ['prev_page'] = $_SERVER ['SERVER_NAME'];
    }
}