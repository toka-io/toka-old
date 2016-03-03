<?php
require_once('service/IdentityService.php');

class PasswordController extends Controller
{
    public function get($request, $response) {  
        $match = array();

        if (RequestMapping::map('password', $request['uri'], $match)) {
            include("page/password/password.php");
        }
        else if (RequestMapping::map('password\/reset', $request['uri'], $match)) {
            $result = IdentityService::validatePasswordRecoveryRequest($_GET);
            if ($result['status'] !== ResponseCode::SUCCESS)
                include("page/password/password-reset-invalid.php");
            else
                include("page/password/password-reset.php");
        }
        else
            parent::redirect404();
    }
    
    public function post($request, $response) {
        $match = array();
        
        if (RequestMapping::map('password', $request['uri'], $match)) {
            $response = IdentityService::recoverPassword($_POST, $response);
            include("page/password/password.php");
        } 
        else if (RequestMapping::map('password\/reset', $request['uri'], $match)) { 
            $response = IdentityService::resetPassword($_POST, $response);
            include("page/password/password-reset.php");
        } 
        else
            parent::redirectRS404();
    }
}