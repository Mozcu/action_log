<?php

use App\Controller;

// Registering controllers
$app['index_controller'] = $app->share(function() use ($app) {
    return new Controller\IndexController($app);
});

$app['song_controller'] = $app->share(function() use ($app) {
    return new Controller\SongController($app);
});

$app['download_controller'] = $app->share(function() use ($app) {
    return new Controller\DownloadController($app);
});

// Routes
$app->get('/', "index_controller:index");

// Song
$app->post('/songs', 'song_controller:create');
$app->get('/songs', 'song_controller:get');

// Album Downloads
$app->post('/downloads', 'download_controller:create');
$app->get('/downloads', 'download_controller:get');