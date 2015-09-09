<?php

class Model
{
    function __construct()
    {
    }
    
    public static function mapToObject($obj, $assocArray) {
        
        if ($assocArray == null)
            return $obj;
        
        foreach ($assocArray as $key => $val) {        
            $key = lcfirst($key);            
            (is_object($obj)) ? $obj->{$key} = $val : $obj[$key] = $val;            
        }
    
        return $obj;
    }
}