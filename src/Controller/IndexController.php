<?php

namespace App\Controller;

class IndexController extends AppController 
{
    public function index()
    {
        return $this->getJSONResponse(['App:', $this->app['parameters']['app.name']]);
    }
    
    public function insert()
    {
        $bq = $this->getService('google_big_query');
        $res = $bq->insert('song', [
            'song_id' => 45,
            'user_id' => 666,
            'album_id' => 999,
            'ip_address' => '127.0.0.1',
            'country' => 'AR',
            'created_at' => date('Y-m-d H:i:s')
        ]);
                
        var_dump($res); die;
    }
    
    public function query()
    {
        $bq = $this->getService('google_big_query');
        $res = $bq->executeSql('SELECT * FROM action_log_dev.song LIMIT 1000');
        var_dump($res); die;
    }
}
