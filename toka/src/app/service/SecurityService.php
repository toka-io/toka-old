<?php
// @model
require_once(__DIR__ . '/../model/UserModel.php');

// @service
require_once(__DIR__ . '/../service/IdentityService.php');

class SecurityService
{
    
    function __construct()
    {
    }
    
    function initialize() {
        $identityService = new IdentityService();
        
        if ($identityService->isUserLoggedIn())
            $_SESSION['user'] = serialize($identityService->getUserSession());        
    }
}