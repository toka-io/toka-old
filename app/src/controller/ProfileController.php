<?php
require_once('service/IdentityService.php');

class ProfileController extends Controller
{
    public function get($request, $response) {
        $match = array();
        
        if (RequestMapping::map('profile\/([a-zA-Z0-9_]{3,25})', $request['uri'], $match)) { // @url: /profile/:username
            $username = $match[1];            
            $available = IdentityService::isUsernameAvailable($username);
            
            if (!$available)
                include("page/profile/profile.php");
            else
                parent::redirect404();
        } 
        else       
            parent::redirect404();
    }
}