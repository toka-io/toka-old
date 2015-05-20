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

require_once(__DIR__ . '/../model/ComponentModel.php');

class BaseController
{
    const MIME_TYPE_APPLICATION_JSON = 'application/json';
    const MIME_TYPE_APPLICATION_OCTET_STREAM = 'application/octet-stream';
    const MIME_TYPE_APPLICATION_XML = 'application/xml';
    const MIME_TYPE_TEXT_HTML = 'text/html';
    const MIME_TYPE_TEXT_PLAIN = 'text/plain';
    
    private $_contentType;
    
    function __construct() 
    {
        $this->_contentType = BaseController::MIME_TYPE_TEXT_PLAIN;
    }
    
    public function getContentType() 
    {
        return $this->_contentType;
    }
    
    /* 
     * @servicePath: The service url, aka the path after the base url i.e. http://baseUrl/service/action
     */ 
    public function parseRequest($servicePath) 
    {
        $result = array();
        preg_match("/\/([a-z]+)\/?([a-z]+)?\/?([a-zA-Z0-9\s]+)?/", $servicePath, $result);
        
        // If the service path is component/service/action, set the service and action
        // Else set only the service
        if (sizeof($result) > 3)
            $service = new ComponentModel($result[1], $result[2], urldecode($result[3]));
        else if (sizeof($result) > 2)
            $service = new ComponentModel("page", $result[1], $result[2]);
        else
            $service = new ComponentModel("page", $result[1], NULL);
        
        return $service;
    }
    
    public function request() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE')
            $response = $this->delete();
        else if ($_SERVER['REQUEST_METHOD'] === 'GET')
            $response = $this->get();
        else if ($_SERVER['REQUEST_METHOD'] === 'PATCH')
            $response = $this->patch();
        else if ($_SERVER['REQUEST_METHOD'] === 'POST')
            $response = $this->post();
        else if ($_SERVER['REQUEST_METHOD'] === 'PUT')
            $response = $this->put();
    }
    
    /*
     * @contentType: A constant MIME_TYPE provided by the BaseController class
     */
    public function setContentType($contentType)
    {
        $this->_contentType = $contentType;
    }
}
