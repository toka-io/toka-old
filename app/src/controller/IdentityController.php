<?php
require_once('service/IdentityService.php');

class IdentityController extends BaseController
{
    function __construct() 
    {
        parent::__construct();
    }
    
    /*
     * @desc: GET services for /login, /logout, /password, /password/verify, /user/:username/available, /signup, /user
     */
    public function get($request, $response) 
    {  
        $match = array();

        if (RequestMapping::map('login', $request['uri'], $match)) { 
        
            // Return login page
            include("page/login.php");
            exit();
            
        } else if (RequestMapping::map('logout', $request['uri'], $match)) {
            
            // Logout user and redirect to home page
            IdentityService::logout();            
            header("Location: https://" . $_SESSION['prev_page']);
            exit();            
            
        } else if (RequestMapping::map('password', $request['uri'], $match)) {
            
            include("page/password/password.php");
            exit();
            
        } else if (RequestMapping::map('password\/reset', $request['uri'], $match)) {
                      
            $result = IdentityService::validatePasswordRecoveryRequest($_GET);
            
            if ($result['status'] !== ResponseCode::SUCCESS) {
                include("page/password/password_reset_invalid.php");
                exit();
            }
            else {
                include("page/password/password_reset.php");
                exit();
            }
            
        } else if (RequestMapping::map('signup', $request['uri'], $match)) {
            
            // Return signup page
            include("page/signup/signup.php");
            exit();
            
        } else if (RequestMapping::map('signup\/verify', $request['uri'], $match)) {
            
            $response = IdentityService::activateUser($_GET, $response);
            
            if ($response['status'] !== ResponseCode::SUCCESS)
                $verified = false;
            else
                $verified = true;
            
            // Return signup page
            include("page/signup/verify_signup.php");
            exit();
            
        } else {
            parent::redirect404();
        }
    }
    
    /*
     * @desc: POST services for /login, /password, /password/verify, /signup
     */
    public function post($request, $response)
    {
        $match = array();
        
        if (RequestMapping::map('login', $request['uri'], $match)) {
            
            // Log in user
            $response = IdentityService::login($_POST, $response);

            // If login was successful, go to home page
            // If login was NOT successful, redirect back to login page
            if ($response['status'] === ResponseCode::SUCCESS) {
                // set previous page to home page if it is null
                header("Location: https://" . $_SESSION['prev_page']);
            }
            else {
                include("page/login.php");
            }
            
            exit();
            
        } else if (RequestMapping::map('password', $request['uri'], $match)) {
            
            // Recover password
            $response = IdentityService::recoverPassword($_POST, $response);
            
            include("page/password/password.php");            
            exit();
            
        } else if (RequestMapping::map('password\/reset', $request['uri'], $match)) { 
            
            // Reset password
            $response = IdentityService::resetPassword($_POST, $response);

            include("page/password/password_reset.php");            
            exit();
            
        } else if (RequestMapping::map('signup', $request['uri'], $match)) {
            
            // Sign up user
            $response = IdentityService::createUser($_POST, $response);
            
            include("page/signup/signup.php");            
            exit();
            
        } else {
            parent::redirectRS404();
        }        
    }
}