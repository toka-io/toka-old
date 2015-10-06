<?php
require_once('utility/Metadata.php');
require_once('repo/MetadataRepo.php');

class MetadataService {
    
    public static function getMetadataByUrl($data) {
        
        $metadataRepo = new MetadataRepo(true);
        $result = $metadataRepo->getMetadataByUrl($data['url']);
        
        if (empty($result)) {
            $result = Metadata::getMeta($data['url']);
            if (isset($result['og:image']))
                $metadataRepo->cacheMetadata($data['url'], $result);
        }                
        
        return Metadata::flattenMetadata($result);
    }
    
    public static function getMetadataArchive($limit) {
    
        $metadataRepo = new MetadataRepo(true);
        $result = $metadataRepo->getMetadataArchive($limit);
        
        $metadataCache = array();
        foreach ($result as $item) {
            $metadataCache[$item['url']] = Metadata::flattenMetadata($item['metadata']);
        }

        return $metadataCache;
    }
}