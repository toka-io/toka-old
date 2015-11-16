<?php
class RequestMapping {
    
    public static function map($url, $requestUrl, &$match) {
        return preg_match("/^\/".$url."\/?(\?.*|)$/", $requestUrl, $match);
    }    
}