<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

class SongController extends AppController 
{
    public function create(Request $request)
    {
        $song = $request->get('song');
        if (is_null($song)) {
            throw new BadRequestHttpException();
        }
        
        $this->getService('song_service')->addSong($song);
        return $this->getJSONResponse(['success' => true]);
    }
    
    public function get(Request $request)
    {
        
    }
}
