<?php
require_once('service/IdentityService.php');

class IdentityController extends Controller
{
    public function get($request, $response) {  
        $match = array();

        if (RequestMapping::map('login', $request['uri'], $match)) { 
            
            include("page/login.php");
            
        } 
        else if (RequestMapping::map('logout', $request['uri'], $match)) {
            
            // Logout user and redirect to home page
            IdentityService::logout();            
            header("Location: https://" . $_SESSION['prev_page']);   
            
        } 
        else if (RequestMapping::map('signup', $request['uri'], $match)) {
            
            // Return signup page
            include("page/signup/signup.php");
            
        } 
        else if (RequestMapping::map('signup\/verify', $request['uri'], $match)) {
            
            $response = IdentityService::activateUser($_GET, $response);
            
            if ($response['status'] !== ResponseCode::SUCCESS)
                $verified = false;
            else
                $verified = true;
            
            // Return signup page
            include("page/signup/verify-signup.php");
        } 
        else
            parent::redirect404();
    }
    
    public function post($request, $response) {
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
        } 
        else if (RequestMapping::map('signup', $request['uri'], $match)) {
            
            // Sign up user
            $response = IdentityService::createUser($_POST, $response);
            include("page/signup/signup.php");
            
        } 
        else
            parent::redirectRS404();  
    }
}