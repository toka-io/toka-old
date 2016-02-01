<?php
require_once('model/Model.php');
require_once('Repository.php');

class CategoryRepo extends Repository
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

    public function getAllCategories() {
        $data = array();
        
        try {
            $collection = new MongoCollection($this->_conn, 'category');
            
            $fields = array('_id' => 0, 'categoryId' => 1, 'categoryName' => 1, 'categoryImgUrl' => 1);
            $cursor = $collection->find(array(), $fields);
            
            foreach ($cursor as $document) {
                $category = new Category();
                $category = Model::mapToObject($category, $document);
                
                array_push($data, $category);
            }
            
        } catch (MongoCursorException $e) {
            $data['error'] = true;
            $data['errorMsg'] = "Could not retrieve all categories! Error: " . $e;
        }
        
        return $data;
    }
}