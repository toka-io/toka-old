<?php

// @model
require_once('model/UserModel.php');

// @repo
require_once('repo/SettingsRepo.php');
require_once('repo/IdentityRepo.php');

class SettingsService
{
    function __construct()
    {
    }

    /*
     * @notes: Returns associative array of settings only, you will have to manually bind if needed
     */
    function getUserSettingsByUsername($username)
    {
    	$identityRepo = new IdentityRepo(true);
    	$user = $identityRepo->getUserByUsername($username);
    	
        $settings = $user->settings;
            
    	return $settings;
    }

    function updateSettingByUser($user, $settings)
    {
        $settingsRepo = new SettingsRepo(true);
        return $settingsRepo->updateSettingByUsername($user->username, $settings);
    }

}