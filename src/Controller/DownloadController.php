<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

class DownloadController extends AppController 
{
    public function create(Request $request)
    {
        $this->getService('download_service')->addDownload($request->request->all());
        return $this->getJSONResponse(['success' => true]);
    }
    
    public function get(Request $request)
    {
        
    }
}
