<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

class DownloadController extends AppController 
{
    public function create(Request $request)
    {
        $download = $request->get('download');
        if (is_null($download)) {
            throw new BadRequestHttpException();
        }
        
        $this->getService('download_service')->addDownload($download);
        return $this->getJSONResponse(['success' => true]);
    }
    
    public function get(Request $request)
    {
        
    }
}
