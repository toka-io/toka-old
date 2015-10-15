<?php
require_once('model/UserModel.php');
require_once('service/IdentityService.php');

class ProfileController extends BaseController
{
    function __construct() 
    {
        parent::__construct();
    }

      /*
     * @desc: GET services for /profile
     */
    public function get($request, $response) 
    {
        $match = array();
        
        if (preg_match('/^\/profile\/([a-zA-Z0-9_]{3,25})\/?$/', $request['uri'], $match)) { // @url: /profile/:username
            
            $username = $match[1];            
            $available = IdentityService::isUsernameAvailable($username);
            
            if (!$available) {
                include("page/profile/profile.php");
                exit();
            } else {
                parent::redirect404();
            }
            
        } else {            
            parent::redirect404();
        }
    }
}