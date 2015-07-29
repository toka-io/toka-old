<?php
require_once('Model.php');

class UserModel extends Model
{
    /*
     * @desc: Never delete users, just update this flag 
     * @expected value: "y" || "n"
     */
    public $active; 
    
    /*
     * @desc: List of followed chatrooms 
     * @@expected value: chatroom id
     */
    public $followedChatrooms;
    
    /*
     * @desc: Username, but the way the user typed it (including caps n' stuff)
     * @expected value: ^[a-zA-Z][a-zA-Z0-9_]{3,16}$
     */
    public $displayName;
    
    /*
     * @desc: User's email
     * @expected value: valid email address
     */
    public $email;
    
    public $hasChatrooms;
    
    public $hasMaxChatrooms;
    
    public $homeChatroom;
    
    /*
     * @desc: User's mute list for all chatrooms
     * @expected value: { chatroomID : [ username, username, ... ] }
     */
    public $muteList;
    
    /*
     * @desc: User's social connections
     * @expected value: []
     */
    public $nakama;
    
    /*
     * @desc: User's password
     * @expected value: md5(salt + password)
     */
    public $password;
    
    /*
     * @desc: User's profile
     * @expected value: JSON object
     */
    public $profile;
    
    /*
     * @desc: User's password salt
     * @expected value: some random word
     */
    public $salt;
    
     /*
     * @desc: User's active session id(s)
     * @expected value: md5(salt + password)
     */
    public $sessions;
    
     /*
     * @desc: User's online status
     * @expected value: " "away" || "busy" || "offline" || online"
     */
    public $status;
    
    /*
     * @desc: User's suspended status, if the timestamp has passed, then the user can access account
     * @expected value: timestamp;
     */
    public $suspended;
    
    /*
     * @desc: User's username
     * @expected value: ^[a-zA-Z][a-zA-Z0-9_]{3,16}$
     */
    public $username;
    
    /*
     * @desc: User's verification code
     * @expected value: Some hash
     */
    public $vCode;
    
    function __construct()
    {
        parent::__construct();
        
        $this->active = "";        
        $this->displayName = "";
        $this->email = "";
        $this->followedChatrooms = array();
        $this->muteList = array();
        $this->nakama = array();
        $this->password = "";
        $this->profile = array();
        $this->salt = "salty";
        $this->sessions = array();
        $this->suspended = "";
        $this->status = "";
        $this->username = "";
        $this->vCode = "";
    }
    
    function activateUser() 
    {
        $this->active = "y";
    }
    
    function addSalt()
    {
        $this->password = md5($this->salt . $this->password);
    }
    
    function deactivateUser()
    {
        $this->active = "n";
    }
    
    function generateVCode()
    {
        $this->vCode = bin2hex(openssl_random_pseudo_bytes(12));
    }
    
    function isValidEmail() 
    {
        $val = preg_match("/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/", $this->email);
        
        return ($val === 1) ? true: false;
    }
    
    /*
     * @desc: Enforce password strength
     */
    function isValidPassword()
    {
        return strlen($this->password) >= 5;
    }
    
    function isValidUsername()
    {
        $val = preg_match("/^[a-z0-9_]{3,25}$/", $this->username);
    
        return ($val === 1) ? true : false;
    }
    
    function setDisplayName($val)
    {
        if (!empty($val))
            $this->displayName = $val;
        else
            $this->displayName = "";
    }
    
    function setEmail($val)
    {
        if (!empty($val))
            $this->email = strtolower($val);
        else
            $this->email = "";
    }
    
    function setPassword($val)
    {
        if (!empty($val))
            $this->password = $val;
        else
            $this->password = "";
    }
    
    function setUsername($val) 
    {
        if (!empty($val))
            $this->username = strtolower($val);
        else
            $this->username = "";
    }
    
    function setVerificationCode($val) 
    {
        if (!empty($val))
            $this->vCode = $val;
        else
            $this->vCode = "";
    }
}