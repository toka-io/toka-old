<?php
require_once('Repository.php');

class MetadataRepo extends Repository
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
    

    public function getMetadataByUrl($url) {
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
    
    public function getMetadataArchive($limit) {
        $data = array();
        
        try {
            $collection = new MongoCollection($this->_conn, 'embed_metadata');
    
            $query = array();
            $cursor = $collection->find($query, array('_id' => 0))->sort(array('cachedDate' => -1))->limit($limit);
            
            foreach ($cursor as $document) {                
                array_push($data, $document);
            }
            
            return $data;
    
        } catch (MongoCursorException $e) {
            return array(
                    'error' => true,
                    'errorMesssage' => "" . $e
            );
        }
    }
    
    public function cacheMetadata($url, $result) {
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