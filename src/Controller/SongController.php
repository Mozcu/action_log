<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

class SongController extends AppController 
{
    public function create(Request $request)
    {
        $this->getService('song_service')->addSong($request->request->all());
        return $this->getJSONResponse(['success' => true]);
    }
    
    public function get(Request $request)
    {
        
    }
}
