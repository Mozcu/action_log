<?php

namespace App\Middleware;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BeforeMiddleware
{

    /**
     *
     * @var Application 
     */
    private $app;

    /**
     *
     * @var Request 
     */
    private $request;

    public function __construct(Application $app, Request $request)
    {
        $this->app = $app;
        $this->request = $request;
    }

    /**
     * Main middelware method, executed in config/app.php
     */
    public function execute()
    {
        $this->auth();
    }

    private function auth()
    {
        if (!is_null($this->request->get('access_token', null))) {
            $token = $this->request->get('access_token');

            if ($this->app['parameters']['security']['token'] == $token) {
                return true;
            }

            throw new HttpException(401, 'invalid acess_token');
        }
        throw new HttpException(401, 'access_token required');
    }

}
