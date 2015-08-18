<?php

class Model
{
    function __construct()
    {
    }
    
    public static function parseMongoObject($obj, $mongoObj) {
        
        foreach ($mongoObj as $key => $val) {
            $newKey = str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));            
            $newKey = lcfirst($newKey);            
            (is_object($obj)) ? $obj->{$newKey} = $val : $obj[$newKey] = $val;            
        }
    
        return $obj;
    }
}