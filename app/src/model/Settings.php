<?php

class Settings
{
	/*
	 * @desc: User's sound setting
	 * @expected value: 0|1|2
	 */
	public $soundNotification;

    /*
     * @desc: User's email setting
     * @expected value: 0|1|2|3
     */
    public $emailNotification;

    function __construct()
    {
        $this->soundNotification = 2;
        $this->emailNotification = 1;
    }

    function setSoundNotification($value)
    {
    	$this->soundNotification = $value;
    }

    function setEmailNotification($value)
    {
        $this->emailNotification = $value;
    }
}