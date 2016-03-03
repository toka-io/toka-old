<?php
require_once('repo/Repository.php');

class TaskRepo extends Repository
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
	
	public function getAllTasks() {
		$tasks = array();
	
		try {
            $collection = new MongoCollection($this->_conn, 'task');
            
            $cursor = $collection->find();
			
			foreach ($cursor as $document) {
                $task = $document;
                
                array_push($tasks, $task);
            }
			
		} catch (MongoCursorException $e) {
        }
		
        return $tasks;
	}
    
    /*
     * @chatroom: Chatroom
     * @user: User
     * @desc: This function mods a user for the chatroom
     * @note: So...this works even if the user doesn't exist...need to make sure we validate that
     */
	 
	 /*
    public function addMod($chatroom, $user) {
        try {
            $collection = new MongoCollection($this->_conn, 'chatroom');
    
            */ /* Check if user is already in chatroom's user list */ /*
            $fields = array('_id' => 0, 'mods' => 1);
            $query = array(
                    'chatroomId' => $chatroom->chatroomId,
                    'mods' => array(
                            'username' => $user->username
                    )
            );
    
            $document = $collection->findOne($query, $fields);
    
            $doesNotExist = empty($document['mods']);
    
            */ /* Add user to chatroom's user list */ /*
            if ($doesNotExist) {
                $user = array(
                        'username' => $user->username
                );
    
                $updateData = array('$push' => array('mods' => $user));
    
                $query = array(
                        'chatroomId' => $chatroom->chatroomId
                );
    
                $collection->update($query, $updateData);
            }
    
            return true;
    
        } catch (MongoCursorException $e) {
            return false;
        }
    }
    */
	
    /*
     * @chatroom: Chatroom
     * @user: User
     * @desc: This function adds a user to a chatroom
     * @note: So...this works even if the user doesn't exist...need to make sure we validate that
     */
	 
	 /*
    public function addUser($chatroom, $user) {
        try {
            $collection = new MongoCollection($this->_conn, 'chatroom');
    
            */ /* Check if user is already in chatroom's user list *//*
            $fields = array('_id' => 0, 'users' => 1);
            $query = array(
                    'chatroomId' => $chatroom->chatroomId,
                    'users' => array(
                            'username' => $user->username
                    )
            );
    
            $document = $collection->findOne($query, $fields);
    
            $doesNotExist = empty($document['users']);
    
            */ /* Add user to chatroom's user list */ /*
            if ($doesNotExist) {
                $user = array(
                        'username' => $user->username
                );
    
                $updateData = array('$push' => array('users' => $user));
    
                $query = array(
                        'chatroomId' => $chatroom->chatroomId
                );
    
                $collection->update($query, $updateData);
            } 
            
            return true;
    
        } catch (MongoCursorException $e) {
            return false;
        }
    }
	*/
    
    /*
     * @user: Chatroom
     * @desc: This function creates a basic chatroom. All validations should be handled in the service calling this function. 
     */
	 
	 /*
    public function createChatroom($newChatroom) {  
        try {           
            $collection = new MongoCollection($this->_conn, 'chatroom');
    
            $document = array(
                'banned' => $newChatroom->banned,
                'categoryName' => $newChatroom->categoryName,
                'chatroomId' => $newChatroom->chatroomId,
                'chatroomName' => $newChatroom->chatroomName,
                'chatroomType' => $newChatroom->chatroomType,
                'coOwners' => $newChatroom->coOwners,
                'guesting' => $newChatroom->guesting,
                'info' => $newChatroom->info,
                'maxSize' => $newChatroom->maxSize,
                'members' => $newChatroom->members,
                'mods' => $newChatroom->mods,
                'owner' => $newChatroom->owner,
                'password' => $newChatroom->password,
                'tags' => $newChatroom->tags
            );
            
            $collection->insert($document);
    
        } catch (MongoCursorException $e) {
            return false;
        }
    
        return true;
    }
    
    public function getChatroomByID($chatroomId) {
        $chatroom = new Chatroom();
    
        try {
            $collection = new MongoCollection($this->_conn, 'chatroom');
    
            $fields = array(
                    '_id' => 0
            );
            $query = array('chatroomId' => $chatroomId);
            
            $chatroom = Model::mapToObject($chatroom, $collection->findOne($query, $fields));
            
        } catch (MongoCursorException $e) {
            $data['error'] = true;
            $data['errorMsg'] = "Could not retrieve chatrooms by chatroom id! Error: " . $e;
        }
    
        return $chatroom;
    }
    
    public function getChatroomsByCategory($category) {
        $data = array();
    
        try {
            $collection = new MongoCollection($this->_conn, 'chatroom');
    
            $fields = array(
                '_id' => 0
            );
            $query = array('categoryName' => $category->categoryName);
            
            $cursor = $collection->find($query, $fields);
            
            foreach ($cursor as $document) {
                $chatroom = new Chatroom();
                $chatroom = Model::mapToObject($chatroom, $document);
                
                array_push($data, $chatroom);
            }
    
        } catch (MongoCursorException $e) {
            $data['error'] = true;
            $data['errorMsg'] = "Could not retrieve chatrooms by category! Error: " . $e;
        }
    
        return $data;
    }
    
    public function getChatroomsByPopularity($category) {
        $data = array();
    
        try {
            $collection = new MongoCollection($this->_conn, 'chatroom');
    
            $fields = array(
                    '_id' => 0
            );
    
            $cursor = $collection->find(array(), $fields);
            $cursor->sort(array("_id" => 1));
            
            foreach ($cursor as $document) {
                $chatroom = new Chatroom();
                $chatroom = Model::mapToObject($chatroom, $document);
                
                array_push($data, $chatroom);
            }
    
        } catch (MongoCursorException $e) {
            $data['error'] = true;
            $data['errorMsg'] = "Could not chatrooms by popularity! Error: " . $e;
        }
    
        return $data;
    }
    
    public function getChatroomsByOwner($user) {
        $data = array();
        
        try {
            $collection = new MongoCollection($this->_conn, 'chatroom');
        
            $fields = array(
                    '_id' => 0
            );
            $query = array('owner' => $user->username);
            
            $cursor = $collection->find($query, $fields);
            
            foreach ($cursor as $document) {
                $chatroom = Model::mapToObject(new Chatroom(), $document);
                
                array_push($data, $chatroom);
            }
        
        } catch (MongoCursorException $e) {
            $data['error'] = true;
            $data['errorMsg'] = "Could not retrieve chatrooms by owner! Error: " . $e;
        }
        
        return $data;
    }
	*/
    
    /*
     * @chatroom: Chatroom
     * @user: User
     * @desc: This function removes a user to a chatroom
     * @note: So...this works even if the user doesn't exist...need to make sure we validate that
     */
	 
	 /*
    public function removeUser($chatroom, $user) {
        try {
            $collection = new MongoCollection($this->_conn, 'chatroom');    

            $user = array(
                    'username' => $user->username
            );

            $updateData = array('$pull' => array('mods' => $user));

            $query = array(
                    'chatroomId' => $chatroom->chatroomId
            );

            $collection->update($query, $updateData);
    
            return true;
    
        } catch (MongoCursorException $e) {
            return false;
        }
    }
	*/
    
    /*
     * @chatroom: Chatroom
     * @user: User
     * @desc: This function removes a user to a chatroom
     * @note: So...this works even if the user doesn't exist...need to make sure we validate that
     */
	 
	 /*
    public function removeMod($chatroom, $user) {
        try {
            $collection = new MongoCollection($this->_conn, 'chatroom');
    
            $user = array(
                    'username' => $user->username
            );
    
            $updateData = array('$pull' => array('mods' => $user));
    
            $query = array(
                    'chatroomId' => $chatroom->chatroomId
            );
    
            $collection->update($query, $updateData);
    
            return true;
    
        } catch (MongoCursorException $e) {
            return false;
        }
    }
	*/
    
    /*
     * @user: Chatroom
     * @desc: This function updates a chatroom's settings
     * @note: Need to look into how to avoid replacing data with data that isn't set on the front-end
     */
	 
	 /*
    public function updateChatroom($chatroom) {
        try {
            $collection = new MongoCollection($this->_conn, 'chatroom');
    
            $query = array(
                'chatroomId' => $chatroom->chatroomId
            );
            
            $fieldsToUpdate = array();
            
            if (!empty($chatroom->categoryName))
                $fieldsToUpdate['categoryName'] = $chatroom->categoryName;
            
            if (!empty($chatroom->chatroomName))
                $fieldsToUpdate['chatroomName'] = $chatroom->chatroomName;
            
            if (!empty($chatroom->chatroomType))
                $fieldsToUpdate['chatroomType'] = $chatroom->chatroomType;
            
            if (!empty($chatroom->guesting))
                $fieldsToUpdate['guesting'] = $chatroom->guesting;
            
            if (!empty($chatroom->maxSize))
                $fieldsToUpdate['maxSize'] = $chatroom->maxSize;
            
            $fieldsToUpdate['info'] = $chatroom->info;
            $fieldsToUpdate['tags'] = $chatroom->tags;
            
            $updateData = array('$set' => $fieldsToUpdate);
    
            $collection->update($query, $updateData);
    
        } catch (MongoCursorException $e) {
            return false;
        }
    
        return true;
    }
	*/
}