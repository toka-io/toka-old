<?php
abstract class Controller {
    
    const MIME_TYPE_APPLICATION_JSON = 'application/json';
    const MIME_TYPE_APPLICATION_OCTET_STREAM = 'application/octet-stream';
    const MIME_TYPE_APPLICATION_XML = 'application/xml';
    const MIME_TYPE_TEXT_HTML = 'text/html';
    const MIME_TYPE_TEXT_PLAIN = 'text/plain';
    
    function __construct() {
    }
    
    /*
     * @servicePath: The service url, aka the path after the base url i.e. http://baseUrl/service/
     */
    public static function getService($request) {
        $result = array ();
        preg_match ( "/\/([a-z]+)(\/.*)?/", $request, $result );
        return (empty ( $result )) ? "" : $result [1];
    }
    
    public static function get500Response($e) {
        return json_encode(array(
                "status" => ResponseCode::INTERNAL_SERVER_ERROR,
                "error" => $e
        ));
    }
    
    public static function redirect404() {
        http_response_code ( 404 );
        include ("error/404.php");
        exit ();
    }
    
    public static function redirect500() {
        http_response_code ( 500 );
        include ("error/500.php");
        exit ();
    }
    
    public static function redirectRS404() {
        http_response_code ( 404 );
        $response ['status'] = ResponseCode::NOT_FOUND;
        $response ['message'] = "not a valid service";
        header ( 'Content-Type: ' . MediaType::MIME_TYPE_APPLICATION_JSON );
        include ("error/rs404.php");
        exit ();
    }
    
    public function request() {
        $request = array ();
        $request ['uri'] = $_SERVER ['REQUEST_URI'];
        $request ['headers'] = getallheaders ();
        $response = array ();
        
        if ($_SERVER ['REQUEST_METHOD'] === 'GET')
            $response = $this->get ( $request, $response );
        else if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
            $request ['data'] = file_get_contents ( 'php://input' );
            $response = $this->post ( $request, $response );
        } else if ($_SERVER ['REQUEST_METHOD'] === 'PUT') {
            $request ['data'] = file_get_contents ( 'php://input' );
            $response = $this->put ( $request, $response );
        } else {
            self::redirectRS404 ();
        }
        
        echo $response;
    }
}
