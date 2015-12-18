<?php 
class InternalController extends BaseController
{
    function __construct() 
    {
        parent::__construct();
    }
    
    public function get($request, $response) 
    {
        $match = array();

        if (RequestMapping::map('test', $request['uri'], $match)) {
            $response['status'] = ResponseCode::SUCCESS;
        
            include("internal/test/bot-test.php");
            exit();
        } else {
            parent::redirectRS404();            
        }              
    }    
}