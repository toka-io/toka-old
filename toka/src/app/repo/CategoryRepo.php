<?php
require_once('Repository.php');

class CategoryRepo extends Repository
{
    // Where do we define host for each repository? Would there ever be a case where we need to connect to different hosts? or always 1 host and then that host manages where it goes...
    private $_host = NULL;
    private $_db = 'tokabox';
    
    // Repository connection
    private $_conn = NULL;
    
    function __construct()
    {
        parent::__construct();
        $mongo = parent::connect($this->_host, $this->_db);
        $this->conn = $mongo->tokabox;
    }

    public function getAllCategories() 
    {
        $data = array();
        
        try {
            $collection = new MongoCollection($this->conn, 'category');
            
            $fields = array('_id' => 0, 'category_id' => 1, 'category_name' => 1, 'category_img_url' => 1);
            $cursor = $collection->find(array(), $fields);
            
            foreach ($cursor as $document) {
                array_push($data, $document);
            }
            
        } catch (MongoCursorException $e) {
            $data['error'] = true;
            $data['errorMsg'] = "Could not retrieve all categories! Error: " . $e;
        }
        
        return $data;
    }
}