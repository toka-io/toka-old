<?php
require_once('Repository.php');
require_once('SettingsModel.php');

class IdentityRepo extends Repository
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
        $mongo;
        if ($write)
            $mongo = parent::connectToPrimary($this->_host, $this->_db);
        else
            $mongo = parent::connectToReplicaSet($this->_host, $this->_db);
        $this->_conn = $mongo->toka;
        $this->_conn->setReadPreference(MongoClient::RP_PRIMARY_PREFERRED);
    }
    
    /*
     * @userModel: UserModel
     * @desc: This function deactivates a user
     */
    public function activateUser($user)
    {
        try {
            $collection = new MongoCollection($this->_conn, 'user');
    
            $updateData = array('$set' => array('active' => "y"));
    
            $query = array(
                    'username' => $user->username
            );
    
            $collection->update($query, $updateData);
    
        } catch (MongoCursorException $e) {
            return false;
        }
    
        return true;
    }
    
    /*
     * @userModel: UserModel
     * @chatroom: ChatroomModel
     * @desc: This function adds a chatroom to a user
     * @note: So...this works even if the user doesn't exist...need to make sure we validate that
     */
    public function addChatroom($user, $chatroom)
    {
        try {
            $collection = new MongoCollection($this->_conn, 'user');
    
            /* Check if chatroom is already in user's chatroom list */            
            $fields = array('_id' => 0, 'chatrooms' => 1);
            $query = array(
                'username' => $user->username,
                'chatrooms' => array(
                    'chatroom_id' => $chatroom->chatroomId
                )
            );
            
            $document = $collection->findOne($query, $fields);
            
            $doesNotExist = empty($document['chatrooms']);
            
            /* Add chatroom to user's chatroom list */
            if ($doesNotExist) {
                // Change chatroom to an associative array for update                
                $chatroom = array(
                    'chatroom_id' => $chatroom->chatroomId
                ); 
                
                $updateData = array('$push' => array('chatrooms' => $chatroom));
        
                $query = array(
                        'username' => $user->username
                );
        
                $collection->update($query, $updateData);
            }
            
            return true;
    
        } catch (MongoCursorException $e) {
            return false;
        }
    }

    /*
     * @userModel: UserModel
     * @desc: This function retrieves the user by username and then applies the salt to the password and returns true if the hashed password matches
     */
    public function checkUserPassword($user) 
    {
        try {
            $existingUser = $this->getUserByUsername($user);

            $user->salt = $existingUser['salt'];
            $user->addSalt();
            
            return $user->password === $existingUser['password'];
            
        } catch (MongoCursorException $e) {
            return false;
        }
        
        return false;
    }
    
    /*
     * @userModel: UserModel
     * @desc: This function creates a basic user. All validations should be handled in the service calling this function. 
     */
    public function createUser($newUser)
    {  
        try {           
            $collection = new MongoCollection($this->_conn, 'user');
    
            $document = array(
                    'active' => "n",                                                            
                    'display_name' => $newUser->displayName,
                    'email' => $newUser->email,
                    'followed_chatrooms' => $newUser->followedChatrooms,
                    'joined_date' => new MongoDate(),
                    'mute_list' => $newUser->muteList,
                    'nakama' => $newUser->nakama,
                    'password' => $newUser->password,
                    'profile' => $newUser->profile,
                    'salt' => $newUser->salt,
                    'sessions' => $newUser->sessions,
                    'settings' => new SettingsModel(),
                    'status' => $newUser->status,
                    'suspended' => $newUser->suspended,
                    'username' => $newUser->username,
                    'v_code' => $newUser->vCode
            );
            
            $collection->insert($document, array("w" => "majority"));
    
        } catch (MongoCursorException $e) {
            return false;
        }
    
        return true;
    }
    
    /*
     * @userModel: UserModel
     * @desc: This function deactivates a user
     */
    public function deactivateUser($user)
    {
        try {
            $collection = new MongoCollection($this->_conn, 'user');

            $updateData = array('$set' => array('active' => "n"));
            
            $query = array(
                'username' => $user->username
            );
            
            $collection->update($query, $updateData);
    
        } catch (MongoCursorException $e) {
            return false;
        }
    
        return true;
    }
    
    /*
     * @note: Documents are associatve arrays and are NOT objects, so you need ao bind function()
     *  in the model to bind to a document...
     */
    public function getUserByEmail($user)
    {
        try {
            $collection = new MongoCollection($this->_conn, 'user');
    
            $query = array('email' => $user->email);
            
            return $collection->findOne($query);
    
        } catch (MongoCursorException $e) {
            return array();
        }
    }
    
    /*
     * @note: Documents are associatve arrays and are NOT objects, so you need ao bind function()
     *  in the model to bind to a document...
     */
    public function getUserByUsername($user)
    {    
        try {
            $collection = new MongoCollection($this->_conn, 'user');
    
            $query = array('username' => $user->username);
            
            return $collection->findOne($query);
    
        } catch (MongoCursorException $e) {
             return array();
        }
    }
    
    /*
     * @note: Documents are associatve arrays and are NOT objects, so you need ao bind function()
     *  in the model to bind to a document...
     */
    public function getSessionsByUsername($user)
    {
        try {
            $collection = new MongoCollection($this->_conn, 'user');
    
            $fields = array('_id' => 0, 'sessions' => 1);
            $query = array('username' => $user->username);
            
            $document = $collection->findOne($query, $fields);
    
            return $document['sessions'];
    
        } catch (MongoCursorException $e) {
            return array();
        }
    }
    
    /*
     * @userModel: UserModel
     * @desc: This checks if the user is active
     */
    public function isActive($user)
    {
        try {
            $existingUser = $this->getUserByUsername($user);
    
            $active = $existingUser['active'];
    
            return ($active === "n") ? false : true;
    
        } catch (MongoCursorException $e) {
            return false;
        }
    
        return false;
    }
    
    /*
     * @note: Documents are associatve arrays and are NOT objects, so you need ao bind function()
     *  in the model to bind to a document...
     */
    public function isEmailAvailable($email)
    {
        try {
            $collection = new MongoCollection($this->_conn, 'user');
    
            $query = array(
                    'email' => $email,
                    'active' => 'y'
            );
    
            $document = $collection->findOne($query);
    
            return (is_null($document)) ? true : false;
    
        } catch (MongoCursorException $e) {
            return array();
        }
    }
    
    /*
     * @note: Documents are associatve arrays and are NOT objects, so you need ao bind function()
     *  in the model to bind to a document...
     */
    public function isUser($user)
    {
        try {
            $collection = new MongoCollection($this->_conn, 'user');
    
            $query = array(
                'username' => $user->username                  
            );
    
            $document = $collection->findOne($query);
    
            return (!is_null($document)) ? true : false;
            
        } catch (MongoCursorException $e) {
            return array();
        }
    }
    
    /*
     * @note: Documents are associatve arrays and are NOT objects, so you need ao bind function()
     *  in the model to bind to a document...
     */
    public function isUsernameAvailable($username)
    {
        try {
            $collection = new MongoCollection($this->_conn, 'user');
    
            $query = array(
                    'username' => $username,
                    'active' => 'y'
            );
    
            $document = $collection->findOne($query);
    
            return (is_null($document)) ? true : false;
    
        } catch (MongoCursorException $e) {
            return array();
        }
    }
    
    /*
     * @userModel: UserModel
     * @desc: This checks if the verification code is valid
     */
    public function isValidVerificationCode($user)
    {
        try {
            $existingUser = $this->getUserByUsername($user);
    
            $vCode = $existingUser['v_code'];
            
            return ($user->vCode === $vCode);
    
        } catch (MongoCursorException $e) {
            return false;
        }
    
        return false;
    }
    
    /*
     * @userModel: UserModel
     * @desc: This function updates the user for login and starts session
     */
    public function login($user)
    {
        try {            
            $collection = new MongoCollection($this->_conn, 'user');
            
            $updateData = array('$set' => array(
                    'status' => "online",
                    'sessions' => $user->sessions
            ));
            
            $query = array(
                    'username' => $user->username
            );
            
            $collection->update($query, $updateData);
            
            return true;
    
        } catch (MongoCursorException $e) {
            return false;
        }
    }
    
    /*
     * @userModel: UserModel
     * @desc: This function logs user out and kills session
     */
    public function logout($user) 
    {      
        try {
            $collection = new MongoCollection($this->_conn, 'user');
            
            $updateData = array('$set' => array(
                    'status' => "offline",
                    'sessions' => array()
            ));
            
            $query = array(
                    'username' => $user->username
            );
            
            $collection->update($query, $updateData);
            
            return true;
        
        } catch (MongoCursorException $e) {
            return false;
        }
    }
    
    /*
     * @userModel: UserModel
     * @chatroom: ChatroomModel
     * @desc: This function adds a chatroom to a user
     * @note: So...this works even if the user doesn't exist...need to make sure we validate that
     */
    public function removeChatroom($user, $chatroom)
    {
        try {
            $collection = new MongoCollection($this->_conn, 'user');
    
            // Change chatroom to an associative array for update
            $chatroom = array(
                    'chatroom_id' => $chatroom->chatroomId
            );

            $updateData = array('$pull' => array('chatrooms' => $chatroom));

            $query = array(
                    'username' => $user->username
            );

            $collection->update($query, $updateData);            
    
            return true;
    
        } catch (MongoCursorException $e) {
            return false;
        }
    }
    
    /*
     * @userModel: UserModel
     * @desc: This function suspends a user
     */
    public function suspendUser($user)
    {
        try {
            $collection = new MongoCollection($this->_conn, 'user');
    
            $updateData = array('$currentDate' => array(
                    'suspended' => array(
                        '$type' => "date"  
                    )
                )              
            );
    
            $query = array(
                    'username' => $user->username
            );
    
            $collection->update($query, $updateData);
    
        } catch (MongoCursorException $e) {
            return false;
        }
    
        return true;
    }
}