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
     * @notes: Returns associative array, you will have to manually bind if needed
     */
    function getUserByUsername($username)
    {
    	$user = new UserModel();
    	$user->setUsername($username);
    	$identityRepo = new IdentityRepo(true);
    	$user = $identityRepo->getUserByUsername($user);
    	return $user;
    }

    function updateSettingByUser($user, $setting, $value)
    {
        $settingsRepo = new SettingsRepo(true);
        return $settingsRepo->updateSettingByUsername($user->username, $setting, $value);
    }

}