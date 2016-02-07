<?php
require_once('Repository.php');

class SettingsRepo extends Repository
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
     * @desc: This function updates the settings of a user
     */
    public function updateSettingByUsername($username, $settings) {
        try {
            $collection = new MongoCollection($this->_conn, 'user');

            $query = array('username' => $username);
            foreach ($settings as $key => $value) {
                $updateData = array('$set' => array('settings.'.$key => $value));
                $collection->update($query, $updateData);
            }
            return true;
            
        } catch (MongoCursorException $e) {
        	return false;
        }
    }
}