<?php

class RedesignController extends Controller
{
    public function get($request, $response) {
        $match = array();

        if (RequestMapping::map('redesign\/wireframe', $request['uri'], $match)) {
            include("template/wireframe.php");
        }        
        else
            parent::redirect404();

    }
}