<?php

class Model
{
    function __construct()
    {
    }
    
    public static function parseMongoObject($obj, $mongoObj) {
        
        if ($mongoObj == null)
            return $obj;
        
        foreach ($mongoObj as $key => $val) {        
            $key = lcfirst($key);            
            (is_object($obj)) ? $obj->{$key} = $val : $obj[$key] = $val;            
        }
    
        return $obj;
    }
}