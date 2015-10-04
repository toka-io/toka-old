<?php
require_once('BaseController.php');
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
        
            $searchService = new SearchService();
            $response['status'] = 200;
            $response['result'] = $searchService->searchChatroomsByName($name);
        
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } else if (preg_match('/^\/rs\/user\/([a-zA-Z0-9_]{3,25})\/available\/?$/', $request['uri'], $match)) {
        
            $username = $match[1];        
            $identityService = new IdentityService();
            $response['available'] = $identityService->isUsernameAvailable($username);
        
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } else if (preg_match('/^\/rs\/user\/search\/?[^\/]*/', $request['uri'], $match)) {
        
            if (isset($_GET['u']))
                $username = $_GET['u'];
            else
                return json_encode($response);
        
            $searchService = new SearchService();
            $response['status'] = 200;
            $response['result'] = $searchService->searchUsersByUsername($username);
        
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
        
        } else {
            
            $response['status'] = ResponseCode::NOT_FOUND;
            $response['message'] = "not a valid service";
            http_response_code(404);
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
            
        }              
    }    
    
    public function post($request, $response)
    {
        $match = array();
        
        if (preg_match('/^\/rs\/login\/?$/', $request['uri'], $match)) {
            
            // Log in user
            $identityService = new IdentityService();
            $response = $identityService->login($_POST, $response);
            
            return json_encode($response);
            
        } else if (preg_match('/^\/rs\/web\/meta\/fetch\/?$/', $request['uri'], $match)) {

            $data = json_decode($request['data'], true);
            
            $metadataService = new MetadataService();
            $result = $metadataService->getMetadata($data);
            
            $response['status'] = 200;
            $response['result'] = $result; 
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);

        } else {
            
            $response['status'] = ResponseCode::NOT_FOUND;
            $response['message'] = "not a valid service";
            http_response_code(404);
            header('Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON);
            return json_encode($response);
            
        }        
    }
}