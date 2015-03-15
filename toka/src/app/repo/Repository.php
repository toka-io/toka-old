<?php

/**
 * Short description for file
*
* Long description for file (if any)...
*
* PHP version 5
*
* LICENSE: This source file is subject to version 3.01 of the PHP license
* that is available through the world-wide-web at the following URI:
* http://www.php.net/license/3_01.txt.  If you did not receive a copy of
* the PHP License and are unable to obtain it through the web, please
* send a note to license@php.net so we can mail you a copy immediately.
*
* @category   CategoryName
* @package    PackageName
* @author     Original Author <author@example.com>
* @author     Another Author <another@example.com>
* @copyright  1997-2005 The PHP Group
* @license    http://www.php.net/license/3_01.txt  PHP License 3.01
* @version    SVN: $Id$
* @link       http://pear.php.net/package/PackageName
* @see        NetOther, Net_Sample::Net_Sample()
* @since      File available since Release 1.2.0
* @deprecated File deprecated in Release 2.0.0
*/

/*
 * Place includes, constant defines and $_GLOBAL settings here.
* Make sure they have appropriate docblocks to avoid phpDocumentor
* construing they are documented by the page-level docblock.
*/

/**
 * Short description for class
*
* Long description for class (if any)...
*
* @category   CategoryName
* @package    PackageName
* @author     Original Author <author@example.com>
* @author     Another Author <another@example.com>
* @copyright  1997-2005 The PHP Group
* @license    http://www.php.net/license/3_01.txt  PHP License 3.01
* @version    Release: @package_version@
* @link       http://pear.php.net/package/PackageName
* @see        NetOther, Net_Sample::Net_Sample()
* @since      Class available since Release 1.2.0
* @deprecated Class deprecated in Release 2.0.0
*/

class Repository
{
    
    private $_appID = 'toka';
    private $_password = 'Mir@c!3!23';
    private $_host = "";
    
    function __construct()
    {
    }
    
    public function connectToPrimary($host, $db)
    {
        try {
            //             return new MongoClient('mongodb://localhost:27017/' . $db, array("username" => $this->_appID, "password" => $this->_password)); // connects to localhost:27017
            return new MongoClient('mongodb://ds055021-a0.mongolab.com:55021/toka', array("username" => $this->_appID, "password" => $this->_password));
        } catch (MongoConnectionException $e) {
            var_dump('Toka could not create a connection: ' . $e);
        }
    }
    
    // Later make it so you cannot call this if there is an active connection!
    // Also consider the need of having to connect to multiple databases/hosts
    public function connectToReplicaSet($host, $db)
    {        
        try {
//             return new MongoClient('mongodb://localhost:27017/' . $db, array("username" => $this->_appID, "password" => $this->_password)); // connects to localhost:27017
            return new MongoClient('mongodb://@ds055021-a0.mongolab.com:55021,ds055021-a1.mongolab.com:55021/toka', array("username" => $this->_appID, "password" => $this->_password));
        } catch (MongoConnectionException $e) {
            var_dump('Toka could not create a connection: ' . $e);
        }
    }
}