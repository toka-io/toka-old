<?php
require_once('service/IdentityService.php');
require_once('service/SettingsService.php');

class SettingsController extends Controller
{
    public function get($request, $response) {
        $match = array();

        if (RequestMapping::map('settings', $request['uri'], $match)) {
            
             if (IdentityService::isUserLoggedIn()) {
                $user = unserialize($_SESSION['user']);
                $userSettings = SettingsService::getUserSettingsByUsername($user->username);
    	        include("page/settings.php"); 
            }
            else
                include("page/login.php");
        }
        else
            include("page/login.php");
    }

    public function put($request, $response) {
        $match = array();

        if (RequestMapping::map('settings\/update', $request['uri'], $match)) {
            $data = json_decode($request['data'], true);
            $user = IdentityService::getUserSession();

            $response['result'] = SettingsService::updateSettingByUser($user, $data);
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } else
            parent::redirectRS404();
    }
}