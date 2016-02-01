<?php

class Repository
{       
    public function connectToPrimary($host, $db) {
        $config = $GLOBALS['config'];
        try {
            return new MongoClient($config['mongodb']['primary'], $config['mongodb']['auth']);
        } catch (MongoConnectionException $e) {
            var_dump('Toka could not create a connection: ' . $e);
        }
    }
    
    // Later make it so you cannot call this if there is an active connection!
    // Also consider the need of having to connect to multiple databases/hosts
    public function connectToReplicaSet($host, $db) {   
        $config = $GLOBALS['config'];
        try {
            return new MongoClient($config['mongodb']['replicaSet'], $config['mongodb']['auth']);
        } catch (MongoConnectionException $e) {
            var_dump('Toka could not create a connection: ' . $e);
        }
    }
}