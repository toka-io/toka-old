<?php
class Metadata {
    
    function __construct() {}
    
    public static function getMeta($url) {
        $metaInfo = array();
        $sites_html = file_get_contents($url);
        
        $html = new DOMDocument();
        @$html->loadHTML($sites_html);
        
        // Get all meta tags and loop through them.
        foreach($html->getElementsByTagName('meta') as $meta) {
            $metaInfo[$meta->getAttribute('property')] = $meta->getAttribute('content');
            $metaInfo[$meta->getAttribute('name')] = $meta->getAttribute('content');
        }
        
        foreach($html->getElementsByTagName('link') as $meta) {
            if ($meta->getAttribute('rel') == "shortcut icon")
                $metaInfo[$meta->getAttribute('rel')] = $meta->getAttribute('href');
        }
        
        return $metaInfo;
    }
}