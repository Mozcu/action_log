<?php

namespace App\Controller;

class AppController 
{
    
    /**
     *
     * @var MyApplication 
     */
    protected $app;
    
    public function __construct(\Silex\Application $app) {
        $this->app = $app;
    }
    
    /**
     * 
     * @param string $route
     * @param array $parameters
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToRoute($route, array $parameters = [])
    {
        return $this->app->redirect($this->app["url_generator"]->generate($route, $parameters));
    }
    
    /**
     * 
     * @param string $service
     * @return mixed
     */
    protected function getService($service)
    {
        return $this->app[$service];
    }
    
    /**
     * 
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest()
    {
        return $this->app['request'];
    }
    
    /**
     * 
     * @param array $content
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getJSONResponse(array $content = array()) {
        return $this->app->json($content);
    }
    
    /**
     * 
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    protected function getSession()
    {
        return $this->app['session'];
    }
}
