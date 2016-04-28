<?php

$app['google_api'] = $app->share(function ($app) {
    return new App\Lib\GoogleApiConnector($app['parameters']['google_api']);
});

$app['google_big_query'] = $app->share(function ($app) {
    return new App\Lib\GoogleBigQueryService($app['google_api'], $app['parameters']['big_query']['dataset']);
});

$app['song_service'] = $app->share(function ($app) {
    return new App\Service\SongService($app['google_big_query'], $app['parameters']['big_query']['dataset']);
});

$app['download_service'] = $app->share(function ($app) {
    return new App\Service\AlbumDownloadService($app['google_big_query'], $app['parameters']['big_query']['dataset']);
});

// Repositories
/*
 * $app['user_repository'] = $app->share(function ($app) {
    return new App/Repository/UserRepository($app['orm.em'], 'user');
});
 */
