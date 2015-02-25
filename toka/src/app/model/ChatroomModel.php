<?php
require_once('Model.php');

class ChatroomModel extends Model
{
    /*
     * @desc: Chatroom's category
     * @@expected value: valid category_name
     */
    public $categoryName;    
    
    /*
     * @desc: Chatroom's id, also used for public url
     * @expected value: some hased string...?
     */
    public $chatroomID; 
    
    /*
     * @desc: Chatroom name
     * @@expected value: string
     */
    public $chatroomName;
    
    /*
     * @desc: Chatroom type
     * @expected value: 'public' || 'private' || maybe more options later
     */
    public $chatroomType;
    
    /*
     * @desc: Determines whether non-users can join the chatroom, default is 'y'
     * @expected value: 'y' || 'n'
     */
    public $guesting;
    
    /*
     * @desc: Number of people who can join a chatroom
     *      have to see how to only count people typing
     *      ...or do we want to limit it to everyone?
     * @expected value: string
     */
    public $maxSize;
    
    /*
     * @desc: Chatroom moderators
     * @expected value: []
     */
    public $mods;
    
    /*
     * @desc: Chatroom owner
     * @expected value: string
     */
    public $owner;
    
    /*
     * @desc: Chatroom owner
     * @expected value: string
     */
    public $tags;
    
    /*
     * @desc: List of all users in chatroom
     * @expected value: []
     */
    public $users;
    
    function __construct()
    {
        parent::__construct();
        
        $this->categoryName = "";
        $this->chatroomID = "123";
        $this->chatroomName = "";
        $this->guesting = "n";
        $this->maxSize = 20;
        $this->mods = array();
        $this->owner = "";
        $this->tags = array();
        $this->users = array();
    }
    
    function setCategoryName($val) 
    {
        if (!empty($val))
            $this->categoryName = $val;
        else
            $this->categoryName = "";
    }
    
    function setChatroomID($val)
    {
        if (!empty($val))
            $this->chatroomID = $val;
        else
            $this->chatroomID = "";
    }
    
    function setChatroomName($val)
    {
        if (!empty($val))
            $this->chatroomName = $val;
        else
            $this->chatroomName = "";
    }
    
    function setChatroomType($val)
    {
        if (!empty($val))
            $this->chatroomType = $val;
        else
            $this->chatroomType = "";
    }
    
    function setGuesting($val) 
    {
        if (!empty($val) && ($val === "true" || $val === "y"))
            $this->guesting = "y";
        else
            $this->guesting = "n";
    }
    
    function setMaxSize($val)
    {
        if (!empty($val) && is_numeric($val))
            $this->maxSize = $val;
        else
            $this->maxSize = 20;
    }
    
    function setOwner($val)
    {
        if (!empty($val))
            $this->owner = $val;
        else
            $this->owner = "";
    }
    
    
}