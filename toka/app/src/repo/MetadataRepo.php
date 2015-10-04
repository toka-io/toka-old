<?php
require_once('Repository.php');

class MetadataRepo extends Repository
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
    

    public function getMetadata($url)
    {
        try {
            $collection = new MongoCollection($this->_conn, 'embed_metadata');
    
            $query = array('url' => $url);
            $document = $collection->findOne($query, array('_id' => 0, 'metadata' => 1));
    
            return (isset($document['metadata'])) ? $document['metadata'] : $document;
    
        } catch (MongoCursorException $e) {
            return array(
                    'error' => true,
                    'errorMesssage' => "" . $e
            );
        }
    }
    
    public function cacheMetadata($url, $result)
    {
        try {
            unset($result['']);
            $collection = new MongoCollection($this->_conn, 'embed_metadata');
            
            $collection->update(
                array(
                    'url' => $url
                ), 
                array('$set' => array(
                        'url' => $url,                                                            
                        'metadata' => $result,
                        'cachedDate' => new MongoDate()
                    )   
                ),
                array('upsert' => true)
            );
    
        } catch (MongoCursorException $e) {
            return false;
        }
    
        return true;
    }
}