<?php
require_once('Repository.php');

class SettingsRepo extends Repository
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
     * @userModel: UserModel
     * @desc: This function updates the settings of a user
     */
    public function updateSettings($user)
    {
        try {
            $collection = new MongoCollection($this->_conn, 'user');

            $query = array(
                    'username' => $user->username
            );

            $updateData = array('$set' => array('settings' => $user->settings));

            $collection->update($query, $updateData);
            return true;
        } catch (MongoCursorException $e) {
        	return false;
        }
    }
}