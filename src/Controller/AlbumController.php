<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

class AlbumController extends AppController 
{
    public function create(Request $request)
    {
        $this->getService('album_service')->addAlbum($request->request->all());
        return $this->getJSONResponse(['success' => true]);
    }
    
    public function get(Request $request)
    {
        
    }
}
