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
    	$user = new UserModel();
    	$user->setUsername($username);
    	$identityRepo = new IdentityRepo(true);
    	$user = $identityRepo->getUserByUsername($user);
        if (isset($user['settings'])) {
            $settings = $user['settings'];
        } else {
            $settings = new UserModel();
            $settings = $settings->settings;
        }
    	return $settings;
    }

    function updateSettingByUser($user, $setting, $value)
    {
        $settingsRepo = new SettingsRepo(true);
        return $settingsRepo->updateSettingByUsername($user->username, $setting, $value);
    }

}