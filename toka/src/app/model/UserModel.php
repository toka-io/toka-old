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
     * @desc: List of chatrooms that the user is viewing 
     * @@expected value: chatroom id
     */
    public $chatrooms;
    
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
    
    /*
     * @desc: User's first name
     * @expected value: [a-zA-Z]*
     */
    public $firstName;
    
    /*
     * @desc: User's gender
     * @expected value: "m" || "f"
     */
    public $gender;
    
    /*
     * @desc: User's last name
     * @expected value: [a-zA-Z]*
     */
    public $lastName;
    
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
     * @desc: User's username
     * @expected value: ^[a-zA-Z][a-zA-Z0-9_]{3,16}$
     */
    public $username;
    
    function __construct()
    {
        parent::__construct();
        
        $this->active = "";
        $this->chatrooms = array();
        $this->displayName = "";
        $this->email = "";
        $this->firstName = "";
        $this->gender = "";
        $this->lastName = "";
        $this->nakama = array();
        $this->password = "";
        $this->salt = "salty";
        $this->sessions = array();
        $this->status = "";
        $this->username = "";
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
    
    function isValidUsername() 
    {
        $val = preg_match("/^[a-z][a-z0-9_]{3,16}$/", $this->username);
        
        return ($val === 1) ? true : false;
    }
    
    /*
     * @desc: Enforce password strength
     */
    function isValidPassword()
    {
        return strlen($this->password) >= 5;
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
            $this->email = $val;
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
}