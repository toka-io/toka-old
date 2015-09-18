<?php
require_once('Model.php');

class SettingsModel extends Model
{
	/*
	 * @desc: User's sound setting
	 * @expected value: 0|1|2
	 */
	public $soundNotification;

    function __construct()
    {
        parent::__construct();
        
        $this->soundNotification = 2;
    }

    function setSoundNotification($value)
    {
    	$this->soundNotification = $value;
    }
}