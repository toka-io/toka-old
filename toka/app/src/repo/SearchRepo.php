<?php
require_once('Repository.php');

class SearchRepo extends Repository
{
    // Where do we define host for each repository? Would there ever be a case where we need to connect to different hosts? or always 1 host and then that host manages where it goes...
    // Remove host if we don't need to differentiate 
    private $_host = NULL;
    private $_db = 'toka';
    
    // Repository connection
    private $_conn = NULL;
    
    function __construct($write)
    {
        parent::__construct();
        if ($write)
            $mongo = parent::connectToPrimary($this->_host, $this->_db);
        else
            $mongo = parent::connectToReplicaSet($this->_host, $this->_db);
        $this->_conn = $mongo->toka;
        $this->_conn->setReadPreference(MongoClient::RP_PRIMARY_PREFERRED);
    }
    

    public function searchChatroomsByName($name)
    {
        try {
            $collection = new MongoCollection($this->_conn, 'chatroom');
    
            $regex = new MongoRegex("/^$name/i");
            $query = array('chatroomName' => $regex);
            $cursor = $collection->find($query, array('_id' => 0, 'chatroomName' => 1))->sort(array('chatroomName' => 1))->limit(10);
    
            $searchResult = array();
            foreach ($cursor as $document) {
                array_push($searchResult, $document);
            }
    
            return array('data' => $searchResult);
    
        } catch (MongoCursorException $e) {
            return array(
                    'error' => true,
                    'errorMesssage' => "" . $e
            );
        }
    }
    
    public function searchUsersByUsername($username)
    {
        try {
            $collection = new MongoCollection($this->_conn, 'user');
    
            $regex = new MongoRegex("/^$username/i");
            $query = array('username' => $regex);    
            $cursor = $collection->find($query, array('_id' => 0, 'username' => 1))->sort(array('username' => 1))->limit(5);
    
            $searchResult = array();
            foreach ($cursor as $document) {                   
                array_push($searchResult, $document);
            }
    
            return array('data' => $searchResult);
            
        } catch (MongoCursorException $e) {
            return array(
                    'error' => true,
                    'errorMesssage' => "" . $e
            );
        }
    }
}