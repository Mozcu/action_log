<?php

namespace App\Service;

use App\Lib\GoogleBigQueryService;
use App\Exceptions\ValidationException;

class SongService
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
    
    public function addSong(array $data)
    {
        $this->validate($data);
        
        $data['created_at'] = date('Y-m-d H:i:s');
        
        return $this->bigQueryService->insert('song', $data);
    }
    
    private function validate(array $data)
    {
        if (empty($data['song_id'])) {
            throw new ValidationException('missing song_id field');
        }
        
        if (empty($data['song_owner_id'])) {
            throw new ValidationException('missing song_owner_id field');
        }
    }
}
