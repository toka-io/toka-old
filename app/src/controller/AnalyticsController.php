<?php 
class AnalyticsController extends BaseController
{
    function __construct() 
    {
        parent::__construct();
    }
    
    public function get($request, $response) 
    {
        $match = array();

        if (RequestMapping::map('analytics', $request['uri'], $match)) {
            $response['status'] = ResponseCode::SUCCESS;
        
            include("internal/analytics/home.php");
            exit();
        } else {
            parent::redirectRS404();            
        }              
    }    
}