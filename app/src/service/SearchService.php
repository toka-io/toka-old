<?php
require_once('repo/SearchRepo.php');

class SearchService
{        
    public static function searchChatroomsByName($name) {
        $searchRepo = new SearchRepo(false);
    
        $result = $searchRepo->searchChatroomsByName($name);
    
        if (isset($result['error']))
            return array();
        else
            return $result['data'];
    }
    
    public static function searchUsersByUsername($username) {
        $searchRepo = new SearchRepo(false);
        
        $result = $searchRepo->searchUsersByUsername($username);
        
        if (isset($result['error']))
            return array();
        else
            return $result['data'];
    }
}