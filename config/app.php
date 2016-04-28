<?php

// Silex
use Silex\Application,
    Silex\Provider\ServiceControllerServiceProvider,
    Silex\Provider\SessionServiceProvider,
    Silex\Provider\MonologServiceProvider;

// Symfony
use Symfony\Component\Yaml\Parser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// App
use App\Middleware\BeforeMiddleware;

$loader = require __DIR__.'/../vendor/autoload.php';

$app = new Application();
$app['root_dir'] = __DIR__ . '/..';

// Parameters
$yaml = new Parser();
$file = $yaml->parse(file_get_contents(__DIR__.'/../config/parameters.yml'));

// This code block replace %variable% with $app['variable']
array_walk_recursive(
    $file,
    function(&$val, $key, $app) {
        $matches = null;
        preg_match('/\%(.*?)\%/', $val, $matches);
        $param = isset($matches[1]) ? $matches[1] : false;
        if ($param) {
            if (isset($app[$param])) {
                $val = str_replace("%$param%", $app[$param], $val);
            }
        }
    },
    $app
);
$app['parameters'] = $file['parameters'];

// Timezone
ini_set("date.timezone", $app['parameters']['app.timezone']);
setlocale(LC_ALL, $app['parameters']['app.locale']);

// Routing
$app->register(new ServiceControllerServiceProvider());
require __DIR__.'/../config/routes.php';

// Session
$app->register(new SessionServiceProvider());
$app['session.storage.handler'] = null;

// Error Log
$app->register(new MonologServiceProvider(), [
    'monolog.logfile' => __DIR__.'/../logs/error.log',
    'monolog.level' => Monolog\Logger::ERROR,
    'monolog.name' => $app['parameters']['app.name']
]) ;

// Services
require __DIR__.'/../config/services.php';

// Middleware
$app->before(function (Request $request, Application $app) {
    $before = new BeforeMiddleware($app, $request);
    $before->execute();
});

// Default Errors
$app->error(function (\Exception $e, $code) use ($app) {
    $code = ($code == 405) ? 404 : $code;
    $allowedCodes = [401, 403, 404];
    if ($app['debug'] || in_array($code, $allowedCodes) || $e instanceof ValidationException) {
        $content = ['code' => $code, 'status' => 'error', 'message' => $e->getMessage()];
    } else {
        if($code == 500 || $e->getCode() == 500) {
            $code = 500;
        } else {
            $code = 400;
        }
        $content = ['code' => $code, 'status' => 'error', 'message' => 'unexpected error, please try again'];
    }

    return $app->json($content, $code);    
});
    
return $app;
