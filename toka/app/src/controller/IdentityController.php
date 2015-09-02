<?php
require_once('BaseController.php');
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

        if (preg_match('/^\/login\/?$/', $request['uri'], $match)) { 
        
            // Return login page
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("page/login.php");
            exit();
            
        } else if (preg_match('/^\/logout\/?$/', $request['uri'], $match)) {
            
            // Logout user and redirect to home page
            $identityService = new IdentityService();
            $identityService->logout();            
            header("Location: https://" . $_SESSION['prev_page']);
            exit();            
            
        } else if (preg_match('/^\/password\/?$/', $request['uri'], $match)) {
            
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("page/password/password.php");
            exit();
            
        } else if (preg_match('/^\/password\/reset?[^\/]*$/', $request['uri'], $match)) {
            
            $identityService = new IdentityService();            
            $result = $identityService->validatePasswordRecoveryRequest($_GET);
            
            if ($result['status'] === 0) {
                header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
                include("page/password/password_reset_invalid.php");
                exit();
            }
            else {
                header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
                include("page/password/password_reset.php");
                exit();
            }
            
        } else if (preg_match('/^\/signup\/?$/', $request['uri'], $match)) {
            
            // Return signup page
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("page/signup/signup.php");
            exit();
            
        } else if (preg_match('/^\/signup\/verify\/?[^\/]*/', $request['uri'], $match)) {
            
            $identityService = new IdentityService();
            $response = $identityService->activateUser($_GET, $response);
            
            if ($response['status'] === 0)
                $verified = false;
            else
                $verified = true;
            
            // Return signup page
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("page/signup/verify_signup.php");
            exit();
            
        } else if (preg_match('/^\/user\/([a-zA-Z0-9_]{3,25})\/available\/?$/', $request['uri'], $match)) { 
            
            $username = $match[1];
            
            // Return if username is available or not
            $identityService = new IdentityService();            
            $response['available'] = $identityService->isUsernameAvailable($username);
            
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
    
    /*
     * @desc: POST services for /login, /password, /password/verify, /signup
     */
    public function post($request, $response)
    {
        $match = array();
        
        if (preg_match('/^\/login\/?$/', $request['uri'], $match)) {
            
            // Log in user
            $identityService = new IdentityService();
            $response = $identityService->login($_POST, $response);

            // If login was successful, go to home page
            // If login was NOT successful, redirect back to login page
            if ($response['status'] === "1") {
                header("Location: https://" . $_SESSION['prev_page']);
            }
            else {
                header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
                include("page/login.php");
            }
            
            exit();
            
        } else if (preg_match('/^\/password\/?$/', $request['uri'], $match)) {
            
            // Recover password
            $identityService = new IdentityService();
            $response = $identityService->recoverPassword($_POST, $response);
            
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("page/password/password.php");            
            exit();
            
        } else if (preg_match('/^\/password\/reset?[^\/]*$/', $request['uri'], $match)) { 
            
            // Reset password
            $identityService = new IdentityService();
            $response = $identityService->resetPassword($_POST, $response);

            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("page/password/password_reset.php");            
            exit();
            
        } else if (preg_match('/^\/signup\/?$/', $request['uri'], $match)) {
            
            // Sign up user
            $identityService = new IdentityService();
            $response = $identityService->createUser($_POST, $response);
            
            header('Content-Type: ' . BaseController::MIME_TYPE_TEXT_HTML);
            include("page/signup/signup.php");            
            exit();
            
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
        $request = array();
        $request['uri'] = $_SERVER['REQUEST_URI'];
        $request['headers'] = getallheaders();    
        $response = array();
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
            $response = $this->get($request, $response);
        else if ($_SERVER['REQUEST_METHOD'] === 'POST')
            $response = $this->post($request, $response);
        else {
            $response['status'] = "-1";
            $response['statusMsg'] = "not a valid service";
            http_response_code(404);
            header('Content-Type: ' . BaseController::MIME_TYPE_APPLICATION_JSON);
            $response = json_encode($response);
        }
        
        echo $response;
    }
}