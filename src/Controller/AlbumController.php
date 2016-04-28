<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AlbumController extends AppController 
{
    public function create(Request $request)
    {
        $album = $request->get('album');
        if (is_null($album)) {
            throw new BadRequestHttpException();
        }
        
        $this->getService('album_service')->addAlbum($album);
        return $this->getJSONResponse(['success' => true]);
    }
    
    public function get(Request $request)
    {
        
    }
}
