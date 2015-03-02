<?php
require_once('Model.php');

class ChatroomModel extends Model
{
    /*
     * @desc: Chatroom moderators
     * @expected value: []
     */
    public $banned;
    
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
     * @note: DEPRECATED, this is handled by chata
     * @desc: List of all users in chatroom
     * @expected value: []
     */
    public $users;
    
    function __construct()
    {
        parent::__construct();
        
        $this->banned = array();
        $this->categoryName = "";
        $this->chatroomID = "";
        $this->chatroomName = "";
        $this->chatroomType = "public";
        $this->guesting = "n";
        $this->maxSize = 20;
        $this->mods = array();
        $this->owner = "";
        $this->tags = array();
    }
    
    /*
     * @note: If some fields are not set, it should be set to the constructor default values
     */
    function bindMongo($mongoObj) 
    {
        //$this->banned = $mongoObj['banned'];
        $this->categoryName = (isset($mongoObj['category_name'])) ? $mongoObj['category_name'] : "";
        $this->chatroomID = (isset($mongoObj['chatroom_id'])) ? $mongoObj['chatroom_id'] : "";
        $this->chatroomName = (isset($mongoObj['chatroom_name'])) ? $mongoObj['chatroom_name'] : "";
        $this->chatroomType = (isset($mongoObj['chatroom_type'])) ? $mongoObj['chatroom_type'] : "public";
        $this->guesting =  (isset($mongoObj['guesting'])) ? $mongoObj['guesting'] : "n";
        $this->maxSize =  (isset($mongoObj['max_size'])) ? $mongoObj['max_size'] : 20;
        $this->mods = (isset($mongoObj['mods'])) ? $mongoObj['mods'] : array();
        $this->owner = (isset($mongoObj['owner'])) ? $mongoObj['owner'] : "";
        $this->tags = (isset($mongoObj['tags'])) ? $mongoObj['tags'] : array();
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