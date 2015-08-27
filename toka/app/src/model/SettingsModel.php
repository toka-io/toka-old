<?php
require_once('Model.php');

class SettingsModel extends Model
{
	/*
	 * @desc: User's sound setting
	 * @expected value: true|false
	 */
	public $soundNotification;

    function __construct()
    {
        parent::__construct();
        
        $this->soundNotification = false;
    }

    function setSoundNotification($value)
    {
    	$this->soundNotification = $value;
    }
}