<?php
require_once('Repository.php');
require_once('model/Settings.php');

class IdentityRepo extends Repository
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
    
    /*
     * @user: User
     * @desc: This function deactivates a user
     */
    public function activateUser($user) {
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
     * @user: User
     * @chatroom: Chatroom
     * @desc: This function adds a chatroom to a user
     * @note: So...this works even if the user doesn't exist...need to make sure we validate that
     */
    public function addChatroom($user, $chatroom) {
        try {
            $collection = new MongoCollection($this->_conn, 'user');
    
            /* Check if chatroom is already in user's chatroom list */            
            $fields = array('_id' => 0, 'chatrooms' => 1);
            $query = array(
                'username' => $user->username,
                'chatrooms' => array(
                    'chatroomId' => $chatroom->chatroomId
                )
            );
            
            $document = $collection->findOne($query, $fields);
            
            $doesNotExist = empty($document['chatrooms']);
            
            /* Add chatroom to user's chatroom list */
            if ($doesNotExist) {
                // Change chatroom to an associative array for update                
                $chatroom = array(
                    'chatroomId' => $chatroom->chatroomId
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
     * @user: User
     * @desc: This function retrieves the user by username and then applies the salt to the password and returns true if the hashed password matches
     */
    public function checkUserPassword($user) {
        try {
            $existingUser = $this->getUserByUsername($user->username);

            $user->salt = $existingUser->salt;
            $user->addSalt();
            
            return $user->password === $existingUser->password;
            
        } catch (MongoCursorException $e) {
            return false;
        }
        
        return false;
    }
    
    /*
     * @user: User
     * @desc: This function creates a basic user. All validations should be handled in the service calling this function. 
     */
    public function createUser($newUser) {  
        try {           
            $collection = new MongoCollection($this->_conn, 'user');
    
            $document = array(
                    'active' => "n",                                                            
                    'displayName' => $newUser->displayName,
                    'email' => $newUser->email,
                    'followedChatrooms' => $newUser->followedChatrooms,
                    'joinedDate' => new MongoDate(),
                    'muteList' => $newUser->muteList,
                    'nakama' => $newUser->nakama,
                    'password' => $newUser->password,
                    'profile' => $newUser->profile,
                    'salt' => $newUser->salt,
                    'sessions' => $newUser->sessions,
                    'settings' => new Settings(),
                    'status' => $newUser->status,
                    'suspended' => $newUser->suspended,
                    'username' => $newUser->username,
                    'vCode' => $newUser->vCode
            );
            
            $collection->insert($document, array("w" => "majority"));
    
        } catch (MongoCursorException $e) {
            return false;
        }
    
        return true;
    }
    
    /*
     * @user: User
     * @desc: This function deactivates a user
     */
    public function deactivateUser($user) {
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
    
    public function getPasswordVCodeByUsername($username) {
        try {
            $collection = new MongoCollection($this->_conn, 'user');
    
            $query = array('username' => $username);
    
            $document = $collection->findOne($query, array('passwordVCode'));
    
            return $document;
    
        } catch (MongoCursorException $e) {
            return array();
        }
    }
    
    public function getEmailByUsername($username) {
        try {
            $collection = new MongoCollection($this->_conn, 'user');
    
            $query = array('username' => $username);
    
            $document = $collection->findOne($query, array('email'));
            
            return $document['email'];
    
        } catch (MongoCursorException $e) {
            return array();
        }
    }
    
    public function getUserByUsername($username) {    
        try {
            $collection = new MongoCollection($this->_conn, 'user');
    
            $query = array('username' => $username);
            
            return Model::mapToObject(new User(), $collection->findOne($query));
    
        } catch (MongoCursorException $e) {
            return new User();
        }
    }
    
    public function getUsernameByEmail($email) {
        try {
            $collection = new MongoCollection($this->_conn, 'user');
    
            $query = array('email' => $email);
    
            $document = $collection->findOne($query, array('username'));
    
            return $document['username'];
    
        } catch (MongoCursorException $e) {
            return "";
        }
    }

    public function getRecentRoomsByUsername($username) {
        try {
            $collection = new MongoCollection($this->_conn, 'user');
    
            $fields = array('_id' => 0, 'recentRooms' => 1);
            $query = array('username' => $username);
    
            $document = $collection->findOne($query, $fields);
            
            return (empty($document)) ? array() : $document['recentRooms'];
    
        } catch (MongoCursorException $e) {
            return array();
        }
    }
    
    public function getSessionsByUsername($user) {
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
     * @user: User
     * @desc: This checks if the user is active
     */
    public function isActive($user) {
        try {
            $existingUser = $this->getUserByUsername($user->username);
    
            return ($existingUser->active === "n") ? false : true;
    
        } catch (MongoCursorException $e) {
            return false;
        }
    
        return false;
    }
    
    /*
     * @note: Documents are associatve arrays and are NOT objects, so you need ao bind function()
     *  in the model to bind to a document...
     */
    public function isEmailAvailable($email) {
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
    public function isUser($user) {
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
    public function isUsernameAvailable($username) {
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
     * @user: User
     * @desc: This checks if the verification code is valid
     */
    public function isValidVerificationCode($user) {
        try {
            $existingUser = $this->getUserByUsername($user->username);
            
            return ($user->vCode === $existingUser->vCode);
    
        } catch (MongoCursorException $e) {
            return false;
        }
    
        return false;
    }
    
    /*
     * @user: User
     * @desc: This function updates the user for login and starts session
     */
    public function login($user) {
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
            echo $e;
            return false;
        }
    }
    
    /*
     * @user: User
     * @desc: This function logs user out and kills session
     */
    public function logout($user) {      
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
     * @user: User
     * @chatroom: Chatroom
     * @desc: This function adds a chatroom to a user
     * @note: So...this works even if the user doesn't exist...need to make sure we validate that
     */
    public function removeChatroom($user, $chatroom) {
        try {
            $collection = new MongoCollection($this->_conn, 'user');
    
            // Change chatroom to an associative array for update
            $chatroom = array(
                    'chatroomId' => $chatroom->chatroomId
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
     * @user: User
     * @desc: This function suspends a user
     */
    public function suspendUser($user) {
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
    
    public function updatePassword($user) {
        try {
            $collection = new MongoCollection($this->_conn, 'user');
    
            $updateData = array('$set' => array(
                    'password' => $user->password
            ));
    
            $query = array(
                    'username' => $user->username
            );
    
            $collection->update($query, $updateData);
    
        } catch (MongoCursorException $e) {
            return false;
        }
    
        return true;
    }
    
    public function updatePasswordVCode($email, $vCode) {
        try {
            $collection = new MongoCollection($this->_conn, 'user');
    
           $updateData = array('$set' => array(
                    'passwordVCode' => array(
                            'code' => $vCode,
                            'createdDate' => new MongoDate()
                    )
            ));
            
            $query = array(
                    'email' => $email
            );
            
            $collection->update($query, $updateData);
    
        } catch (MongoCursorException $e) {
            return false;
        }
    
        return true;
    }
    
    public function updateRecentRooms($username, $room) {
        try {
            $collection = new MongoCollection($this->_conn, 'user');
            $batch = new MongoUpdateBatch($collection);
            
            $pull = array(
                    'q' => array('username' => $username),
                    'u' => array(
                            '$pull' => array(
                                'recentRooms' => $room
                            )
                    )
            
            );
            $push = array(
                    'q' => array('username' => $username),
                    'u' => array(
                        '$push' => array(                            
                            'recentRooms' => array(
                                '$each' => array($room),
                                '$slice' => -10
                            )
                        )   
                    )
                    
            );
            
            $batch->add((object) $pull);
            $batch->add((object) $push);
            $batch->execute();
        
            return true;
        
        } catch (MongoCursorException $e) {
            return false;
        }
    }
}