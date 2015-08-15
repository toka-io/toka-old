<?php
require_once('Model.php');
require_once('SettingsModel.php');

class UserModel extends Model
{
    /*
     * @desc: Never delete users, just update this flag 
     * @expected value: "y" || "n"
     */
    public $active; 
    
    public $chatrooms;
    
    /*
     * @desc: List of followed chatrooms 
     * @@expected value: chatroom id
     */
    public $followedChatrooms;
    
    /*
     * @desc: Username, but the way the user typed it (including caps n' stuff)
     * @expected value: ^[a-zA-Z0-9_]{3,25}}$
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
     * @desc: User's settings 
     * @expected value: Settings Model
     */
    public $settings;
    
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
     * @expected value: ^[a-zA-Z0-9_]{3,25}$
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
        $this->settings = new SettingsModel();
        $this->suspended = "";
        $this->status = "";
        $this->username = "";
        $this->vCode = "";
    }
    
    function addSalt()
    {
        $this->password = md5($this->salt . $this->password);
    }
    
    function setActive($active)
    {
        $this->active = $active;
    }
   
    function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }
    
    function setEmail($email)
    {
       $this->email = strtolower($email);
    }
    
    function setPassword($password)
    {
        $this->password = $password;
 
    }
    
    function setUsername($username) 
    {
        $this->username = strtolower($username);
    }
    
    function setVerificationCode($vCode) 
    {    
        $this->vCode = $vCode;
    }
}