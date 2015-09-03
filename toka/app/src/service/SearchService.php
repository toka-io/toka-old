<?php
require_once('repo/SearchRepo.php');

class SearchService
{    
    function __construct() {}
    
    public function searchChatroomsByName($name) {
        $searchRepo = new SearchRepo(false);
    
        $result = $searchRepo->searchChatroomsByName($name);
    
        if (isset($result['error']))
            return array();
        else
            return $result['data'];
    }
    
    public function searchUsersByUsername($username) {
        $searchRepo = new SearchRepo(false);
        
        $result = $searchRepo->searchUsersByUsername($username);
        
        if (isset($result['error']))
            return array();
        else
            return $result['data'];
    }
}