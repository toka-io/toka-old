<?php

class TimeUtility {
    
    public static function convertMongoDateToDate($mongoDate) {
        return date('Y-m-d H:i:s', $mongoDate->sec);        
    }
    
    public static function getMinuteDifferenceFromNow($time) {
        $unix_now = time();
        $result = strtotime($time, $unix_now);
        $unix_diff_min = (($unix_now  - $result) / 60);
        $min = round($unix_diff_min);
        
        return $min;
    }
}