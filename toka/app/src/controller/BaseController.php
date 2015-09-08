<?php


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
    
    public static function redirect404() {
        http_response_code(404);
        include("error/404.php");
        exit();
    }
    
    public static function redirect500() {
        http_response_code(500);
        include("error/500.php");
        exit();
    }
    
    abstract function request();
}
