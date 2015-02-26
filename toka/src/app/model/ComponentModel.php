<?php
require_once('Model.php');

class ComponentModel extends Model
{
    public $component;
    public $service;
    public $action;
    
    function __construct($component, $service, $action)
    {
        parent::__construct();
        
        $this->component = $component;
        $this->service = $service;
        $this->action = $action;
    }
}