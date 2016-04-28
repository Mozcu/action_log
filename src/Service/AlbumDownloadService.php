<?php

namespace App\Service;

use App\Lib\GoogleBigQueryService;
use App\Exceptions\ValidationException;

class AlbumDownloadService
{
    /**
     *
     * @var GoogleBigQueryService 
     */
    private $bigQueryService;
    
    /**
     *
     * @var string
     */
    private $dataset;
    
    public function __construct(GoogleBigQueryService $bigQueryService, $dataset)
    {
        $this->bigQueryService = $bigQueryService;
        $this->dataset = $dataset;
    }
    
    public function addDownload(array $data)
    {
        $this->validate($data);
        
        $data['created_at'] = date('Y-m-d H:i:s');
        
        return $this->bigQueryService->insert('album_download', $data);
    }
    
    private function validate(array $data)
    {
        if (empty($data['album_id'])) {
            throw new ValidationException('missing album_id field');
        }
        
        if (empty($data['album_owner_id'])) {
            throw new ValidationException('missing album_owner_id field');
        }
    }
}
