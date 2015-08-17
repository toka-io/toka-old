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

abstract class BaseController
{
    const MIME_TYPE_APPLICATION_JSON = 'application/json';
    const MIME_TYPE_APPLICATION_OCTET_STREAM = 'application/octet-stream';
    const MIME_TYPE_APPLICATION_XML = 'application/xml';
    const MIME_TYPE_TEXT_HTML = 'text/html';
    const MIME_TYPE_TEXT_PLAIN = 'text/plain';
    
    function __construct() {}
    
    /*
     * @servicePath: The service url, aka the path after the base url i.e. http://baseUrl/service/
     */
    public static function getService($request)
    {
        $result = array();
        preg_match("/\/([a-z]+)(\/.*)?/", $request, $result);
        return (empty($result)) ? "" : $result[1];
    }
    
    public static function redirect500() {
        http_response_code(500);
        include("error/500.php");
        exit();
    }
    
    abstract function request();
}
