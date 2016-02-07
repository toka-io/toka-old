<?php 
class AnalyticsController extends Controller
{
    public function get($request, $response) {
        $match = array();

        if (RequestMapping::map('analytics', $request['uri'], $match))
            include("internal/analytics/home.php");
        else
            parent::redirect404();
    }    
}