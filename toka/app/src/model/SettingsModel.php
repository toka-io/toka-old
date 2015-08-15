<?php
require_once('Model.php');

class SettingsModel extends Model
{
	/*
	 * @desc: User's sound setting
	 * @expected value: true|false
	 */
	public $soundNotifications;

    function __construct()
    {
        parent::__construct();
        
        $this->soundNotifications = true;
    }
}