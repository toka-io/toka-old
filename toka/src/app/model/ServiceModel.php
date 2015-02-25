<?php
require_once('Model.php');

class ServiceModel extends Model
{
    public $service;
    public $action;
    
    function __construct($service, $action)
    {
        parent::__construct();
        
        $this->service = $service;
        $this->action = $action;
    }
}