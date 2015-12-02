<?php
require_once('model/UserModel.php');
require_once('service/IdentityService.php');
require_once('service/SettingsService.php');

class SettingsController extends BaseController
{
    function __construct() 
    {
        parent::__construct();
    }

      /*
     * @desc: GET services for /settings
     */
    public function get($request, $response) 
    {
        $match = array();

        if (RequestMapping::map('settings', $request['uri'], $match)) {
            
             if (IdentityService::isUserLoggedIn()) {
                $user = unserialize($_SESSION['user']);
                $userSettings = SettingsService::getUserSettingsByUsername($user->username);
                
    	        include("page/settings.php");
        	    exit(); 
            }
            else {
                include("page/login.php");
                exit();
            }
        } else {
            include("page/login.php");
            exit();
        }
    }

     /*
     * @desc: PUT services for /settings
     */
    public function put($request, $response)
    {
        $match = array();

        if (RequestMapping::map('settings\/update', $request['uri'], $match)) { // @url: /settings/update
            
            $data = json_decode($request['data'], true);
            $user = IdentityService::getUserSession();

            $response['result'] = SettingsService::updateSettingByUser($user, $data);
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } else {
            parent::redirectRS404();
        }
    }
}