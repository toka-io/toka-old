<?php
require_once('utility/Metadata.php');
require_once('repo/MetadataRepo.php');

class MetadataService {
    
    public function getMetadata($data) {
        
        $metadataRepo = new MetadataRepo(true);
        $result = $metadataRepo->getMetadata($data['url']);
        
        if (empty($result)) {
            $result = Metadata::getMeta($data['url']);
            if (isset($result['og:image']))
                $metadataRepo->cacheMetadata($data['url'], $result);
        }                
        
        return $result;
    }
}