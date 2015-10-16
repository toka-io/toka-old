<?php
require_once('service/IdentityService.php');
require_once('service/MetadataService.php');
require_once('service/SearchService.php');

class RSController extends BaseController
{
    function __construct() 
    {
        parent::__construct();
    }
    
    public function get($request, $response) 
    {
        $match = array();

        if (preg_match('/^\/rs\/chatroom\/search\/?[^\/]*/', $request['uri'], $match)) {
        
            if (isset($_GET['c']))
                $name = $_GET['c'];
            else
                return json_encode($response);
        
            $response['status'] = ResponseCode::SUCCESS;
            $response['result'] = SearchService::searchChatroomsByName($name);
        
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } else if (preg_match('/^\/rs\/user\/([a-zA-Z0-9_]{3,25})\/available\/?$/', $request['uri'], $match)) {
        
            $username = $match[1];        
            $response['available'] = IdentityService::isUsernameAvailable($username);
        
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } else if (preg_match('/^\/rs\/user\/search\/?[^\/]*/', $request['uri'], $match)) {
        
            if (isset($_GET['u']))
                $username = $_GET['u'];
            else
                return json_encode($response);
        
            $response['status'] = ResponseCode::SUCCESS;
            $response['result'] = SearchService::searchUsersByUsername($username);
        
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } else if (preg_match('/^\/rs\/web\/meta\/?$/', $request['uri'], $match)) {
            
            $result = MetadataService::getMetadataArchive(100);
            
            $response['status'] = ResponseCode::SUCCESS;
            $response['result'] = $result; 
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);

        } else {            
            parent::redirectRS404();            
        }              
    }    
    
    public function post($request, $response)
    {
        $match = array();
        
        if (preg_match('/^\/rs\/login\/?$/', $request['uri'], $match)) {
            
            // Log in user
            $response = IdentityService::login($_POST, $response);
            
            return json_encode($response);
            
        } else if (preg_match('/^\/rs\/web\/meta\/fetch\/?$/', $request['uri'], $match)) {
            try {
                $data = json_decode($request['data'], true);            
                $result = MetadataService::getMetadataByUrl($data);
                
                $response['status'] = ResponseCode::SUCCESS;
                $response['result'] = $result; 
                header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
                return json_encode($response);
            } catch (Exception $e) {
                return parent::get500Response("Could not resolve url!");
            }

        } else {            
            parent::redirectRS404();            
        }        
    }
}