<?php
require_once('Repository.php');

class SearchRepo extends Repository
{
    // Repository connection
    private $_conn = NULL;
    
    function __construct($write) {
        if ($write)
            $mongo = parent::connectToPrimary(NULL, 'toka');
        else
            $mongo = parent::connectToReplicaSet(NULL, 'toka');
        $this->_conn = $mongo->toka;
        $this->_conn->setReadPreference(MongoClient::RP_PRIMARY_PREFERRED);
    }
    

    public function searchChatroomsByName($name) {
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
    
    public function searchUsersByUsername($username) {
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