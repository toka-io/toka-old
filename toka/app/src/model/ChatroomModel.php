<?php
require_once('Model.php');

class ChatroomModel extends Model
{
    const CHATROOM_TYPE_NORMAL = 'normal';
    const CHATROOM_TYPE_USER = 'user';
    const CHATROOM_TYPE_HASHTAG = 'hashtag';
    
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
    public $chatroomId; 
    
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
    
    public $coOwners;
    
    /*
     * @desc: Determines whether non-users can join the chatroom, default is 'y'
     * @expected value: 'y' || 'n'
     */
    public $guesting;
    
    public $info;
    
    public $isChatfeed;
    
    /*
     * @desc: Number of people who can join a chatroom
     *      have to see how to only count people typing
     *      ...or do we want to limit it to everyone?
     * @expected value: string
     */
    public $maxSize;
    
    public $members;
    
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
    
    public $password;
    
    /*
     * @desc: Chatroom owner
     * @expected value: string
     */
    public $tags;
    
    function __construct()
    {
        parent::__construct();
        
        $this->banned = array();
        $this->categoryName = "";
        $this->chatroomId = "";
        $this->chatroomName = "";
        $this->chatroomType = "public";
        $this->coOwners = array();
        $this->guesting = "n";
        $this->info = "";
        $this->maxSize = 20;
        $this->members = array();
        $this->mods = array();
        $this->owner = "";
        $this->password = "";
        $this->tags = array();
    }
    
    /*
     * @note: If some fields are not set, it should be set to the constructor default values
     */
    function bindMongo($mongoObj) 
    {
        $this->banned = (isset($mongoObj['banned'])) ? $mongoObj['banned'] : array();
        $this->categoryName = (isset($mongoObj['category_name'])) ? $mongoObj['category_name'] : "";
        $this->chatroomId = (isset($mongoObj['chatroom_id'])) ? $mongoObj['chatroom_id'] : "";
        $this->chatroomName = (isset($mongoObj['chatroom_name'])) ? $mongoObj['chatroom_name'] : "";
        $this->chatroomType = (isset($mongoObj['chatroom_type'])) ? $mongoObj['chatroom_type'] : "public";
        $this->coOwners = (isset($mongoObj['co_owners'])) ? $mongoObj['co_owners'] : "public";
        $this->guesting =  (isset($mongoObj['guesting'])) ? $mongoObj['guesting'] : "n";
        $this->info =  (isset($mongoObj['info'])) ? $mongoObj['info'] : "";
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
    
    function setChatroomId($val)
    {
        if (!empty($val))
            $this->chatroomId = $val;
        else
            $this->chatroomId = "";
    }
    
    function setChatroomName($val)
    {
        if (!empty($val))
            $this->chatroomName = trim($val);
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
    
    function setInfo($val)
    {
        if (!empty($val))
            $this->info = $val;
        else
            $this->info = "";
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
    
    function setTags($val)
    {
        if (!empty($val))
            $this->tags = $val;
        else
            $this->tags = array();
    }
}