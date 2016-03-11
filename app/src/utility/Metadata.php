<?php
class Metadata {
    
    const USER_AGENT = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36";
    
    public static function getMeta($url) {
        $metaInfo = array();
        $options  = array('http' => array('user_agent' => self::USER_AGENT));
        $type = get_headers($url, 1)["Content-Type"];
        
        if (strpos($type, 'text/html') === false) { 
            return $metaInfo;
        }
        
        $context  = stream_context_create($options);
        $sites_html = file_get_contents($url, false, $context);
        
        $html = new DOMDocument();
        @$html->loadHTML($sites_html);
        
        // Get all meta tags and loop through them.
        foreach($html->getElementsByTagName('meta') as $meta) {
            $metaInfo[$meta->getAttribute('property')] = $meta->getAttribute('content');
            $metaInfo[$meta->getAttribute('name')] = $meta->getAttribute('content');
        }
        return $metaInfo;
    }
    
    public static function flattenMetadata($data) {
        if (isset($data['og:image']))
            $data['image'] = $data['og:image'];
        
        if (isset($data['og:title']))
            $data['title'] = $data['og:title'];
        else if (isset($data['og:site_name']))
            $data['title'] = $data['og:site_name'];
        else
            $data['title'] = "No title available";
        
        if (isset($data['og:description']))
            $data['description'] = $data['og:description'];
        else if (isset($data['Description']))
            $data['description'] = $data['Description'];
        else if (isset($data['description']))
            $data['description'] = $data['description'];
        else
            $data['description'] = "No description available";
        
        return $data;
    }
}