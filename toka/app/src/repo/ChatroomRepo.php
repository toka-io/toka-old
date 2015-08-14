<?php
require_once('Repository.php');

class ChatroomRepo extends Repository
{
    // Where do we define host for each repository? Would there ever be a case where we need to connect to different hosts? or always 1 host and then that host manages where it goes...
    private $_host = NULL;
    private $_db = 'toka';
    
    // Repository connection
    private $_conn = NULL;
    
    function __construct($write)
    {
        parent::__construct();
        $mongo;
        if ($write)
            $mongo = parent::connectToPrimary($this->_host, $this->_db);
        else
            $mongo = parent::connectToReplicaSet($this->_host, $this->_db);
        $this->_conn = $mongo->toka;
        $this->_conn->setReadPreference(MongoClient::RP_PRIMARY_PREFERRED);
    }
    
    /*
     * @chatroom: ChatroomModel
     * @userModel: UserModel
     * @desc: This function mods a user for the chatroom
     * @note: So...this works even if the user doesn't exist...need to make sure we validate that
     */
    public function addMod($chatroom, $user)
    {
        try {
            $collection = new MongoCollection($this->_conn, 'chatroom');
    
            /* Check if user is already in chatroom's user list */
            $fields = array('_id' => 0, 'mods' => 1);
            $query = array(
                    'chatroom_id' => $chatroom->chatroomId,
                    'mods' => array(
                            'username' => $user->username
                    )
            );
    
            $document = $collection->findOne($query, $fields);
    
            $doesNotExist = empty($document['mods']);
    
            /* Add user to chatroom's user list */
            if ($doesNotExist) {
                $user = array(
                        'username' => $user->username
                );
    
                $updateData = array('$push' => array('mods' => $user));
    
                $query = array(
                        'chatroom_id' => $chatroom->chatroomId
                );
    
                $collection->update($query, $updateData);
            }
    
            return true;
    
        } catch (MongoCursorException $e) {
            return false;
        }
    }
    
    /*
     * @chatroom: ChatroomModel
     * @userModel: UserModel
     * @desc: This function adds a user to a chatroom
     * @note: So...this works even if the user doesn't exist...need to make sure we validate that
     */
    public function addUser($chatroom, $user)
    {
        try {
            $collection = new MongoCollection($this->_conn, 'chatroom');
    
            /* Check if user is already in chatroom's user list */
            $fields = array('_id' => 0, 'users' => 1);
            $query = array(
                    'chatroom_id' => $chatroom->chatroomId,
                    'users' => array(
                            'username' => $user->username
                    )
            );
    
            $document = $collection->findOne($query, $fields);
    
            $doesNotExist = empty($document['users']);
    
            /* Add user to chatroom's user list */
            if ($doesNotExist) {
                $user = array(
                        'username' => $user->username
                );
    
                $updateData = array('$push' => array('users' => $user));
    
                $query = array(
                        'chatroom_id' => $chatroom->chatroomId
                );
    
                $collection->update($query, $updateData);
            } 
            
            return true;
    
        } catch (MongoCursorException $e) {
            return false;
        }
    }
    
    /*
     * @userModel: ChatroomModel
     * @desc: This function creates a basic chatroom. All validations should be handled in the service calling this function. 
     */
    public function createChatroom($newChatroom)
    {  
        try {           
            $collection = new MongoCollection($this->_conn, 'chatroom');
    
            $document = array(
                'banned' => $newChatroom->banned,
                'category_name' => $newChatroom->categoryName,
                'chatroom_id' => $newChatroom->chatroomId,
                'chatroom_name' => $newChatroom->chatroomName,
                'chatroom_type' => $newChatroom->chatroomType,
                'co_owners' => $newChatroom->coOwners,
                'guesting' => $newChatroom->guesting,
                'info' => $newChatroom->info,
                'max_size' => $newChatroom->maxSize,
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
    
    public function getChatroomByID($chatroomId)
    {
        $data = array();
    
        try {
            $collection = new MongoCollection($this->_conn, 'chatroom');
    
            $fields = array(
                    '_id' => 0
            );
            $query = array('chatroom_id' => $chatroomId);

            $data = $collection->findOne($query, $fields);
    
        } catch (MongoCursorException $e) {
            $data['error'] = true;
            $data['errorMsg'] = "Could not retrieve chatrooms by chatroom id! Error: " . $e;
        }
    
        return $data;
    }
    
    public function getChatroomsByCategory($category)
    {
        $data = array();
    
        try {
            $collection = new MongoCollection($this->_conn, 'chatroom');
    
            $fields = array(
                '_id' => 0
            );
            $query = array('category_name' => $category->categoryName);
            
            $cursor = $collection->find($query, $fields);
            
            foreach ($cursor as $document) {
                array_push($data, $document);
            }
    
        } catch (MongoCursorException $e) {
            $data['error'] = true;
            $data['errorMsg'] = "Could not retrieve chatrooms by category! Error: " . $e;
        }
    
        return $data;
    }
    
    public function getChatroomsByPopularity($category)
    {
        $data = array();
    
        try {
            $collection = new MongoCollection($this->_conn, 'chatroom');
    
            $fields = array(
                    '_id' => 0
            );
    
            $cursor = $collection->find(array(), $fields);
            $cursor->sort(array("_id" => 1));
            
            foreach ($cursor as $document) {
                array_push($data, $document);
            }
    
        } catch (MongoCursorException $e) {
            $data['error'] = true;
            $data['errorMsg'] = "Could not chatrooms by popularity! Error: " . $e;
        }
    
        return $data;
    }
    
    public function getChatroomsByOwner($user) 
    {
        $data = array();
        
        try {
            $collection = new MongoCollection($this->_conn, 'chatroom');
        
            $fields = array(
                    '_id' => 0
            );
            $query = array('owner' => $user->username);
            
            $cursor = $collection->find($query, $fields);
            
            foreach ($cursor as $document) {
                array_push($data, $document);
            }
        
        } catch (MongoCursorException $e) {
            $data['error'] = true;
            $data['errorMsg'] = "Could not retrieve chatrooms by owner! Error: " . $e;
        }
        
        return $data;
    }
    
    /*
     * @chatroom: ChatroomModel
     * @userModel: UserModel
     * @desc: This function removes a user to a chatroom
     * @note: So...this works even if the user doesn't exist...need to make sure we validate that
     */
    public function removeUser($chatroom, $user)
    {
        try {
            $collection = new MongoCollection($this->_conn, 'chatroom');    

            $user = array(
                    'username' => $user->username
            );

            $updateData = array('$pull' => array('mods' => $user));

            $query = array(
                    'chatroom_id' => $chatroom->chatroomId
            );

            $collection->update($query, $updateData);
    
            return true;
    
        } catch (MongoCursorException $e) {
            return false;
        }
    }
    
    /*
     * @chatroom: ChatroomModel
     * @userModel: UserModel
     * @desc: This function removes a user to a chatroom
     * @note: So...this works even if the user doesn't exist...need to make sure we validate that
     */
    public function removeMod($chatroom, $user)
    {
        try {
            $collection = new MongoCollection($this->_conn, 'chatroom');
    
            $user = array(
                    'username' => $user->username
            );
    
            $updateData = array('$pull' => array('mods' => $user));
    
            $query = array(
                    'chatroom_id' => $chatroom->chatroomId
            );
    
            $collection->update($query, $updateData);
    
            return true;
    
        } catch (MongoCursorException $e) {
            return false;
        }
    }
    
    /*
     * @userModel: ChatroomModel
     * @desc: This function updates a chatroom's settings
     * @note: Need to look into how to avoid replacing data with data that isn't set on the front-end
     */
    public function updateChatroom($chatroom)
    {
        try {
            $collection = new MongoCollection($this->_conn, 'chatroom');
    
            $query = array(
                'chatroom_id' => $chatroom->chatroomId
            );
            
            $fieldsToUpdate = array();
            
            if (!empty($chatroom->categoryName))
                $fieldsToUpdate['category_name'] = $chatroom->categoryName;
            
            if (!empty($chatroom->chatroomName))
                $fieldsToUpdate['chatroom_name'] = $chatroom->chatroomName;
            
            if (!empty($chatroom->chatroomType))
                $fieldsToUpdate['chatroom_type'] = $chatroom->chatroomType;
            
            if (!empty($chatroom->guesting))
                $fieldsToUpdate['guesting'] = $chatroom->guesting;
            
            if (!empty($chatroom->maxSize))
                $fieldsToUpdate['max_size'] = $chatroom->maxSize;
            
            $fieldsToUpdate['info'] = $chatroom->info;
            $fieldsToUpdate['tags'] = $chatroom->tags;
            
            $updateData = array('$set' => $fieldsToUpdate);
    
            $collection->update($query, $updateData);
    
        } catch (MongoCursorException $e) {
            return false;
        }
    
        return true;
    }
}