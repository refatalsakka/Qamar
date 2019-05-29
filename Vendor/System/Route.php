<?php

namespace System;

class Route
{
    private $app;

    private $routes = [];

    public $current = [];

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function add($url, $action, $requestMethos = 'GET', $middlware = [])
    {
        $routes = [
            'url'       => $url,
            'pattern'   => $this->generatePattern($url),
            'action'    => $this->getAction($action),
            'method'    => $requestMethos,
            'middleware' => $middlware
        ];

        $this->routes[] = $routes;

        return $this;
    }

    public function generatePattern($url)
    {
        $pattern = '#^';
        $pattern .= str_replace([':text', ':id'], ['([a-zA-Z0-9-]+)', '(\d+)'], $url);
        $pattern .= '$#';
      
        return $pattern;
    }

    public function getAction($action)
    {
        $action = str_replace('/', '\\', $action);

        $action = (strpos($action, '@') != 0) ? $action : $action . '@index';

        $action = explode('@', $action);
        
        return $action;
    }

    public function getProperRoute()
    {
        foreach($this->routes as $route) {

            if ($this->isMatching($route['pattern']) && $this->isMatchingRequestMethod($route['method'])) {

                if (! empty($route['middleware'])) {
                  
                    if (is_array($route['middleware'])) {
                        
                        foreach($route['middleware'] as $class) {
    
                            $this->middleware($class);
                        }
    
                    } else {
    
                        $this->middleware($route['middleware']);
                    }
                }

                $this->current = $route;

                list($controller, $method) = $route['action'];

                $arguments = $this->getArgumentsFor($route['pattern']);

                return [$controller, $method, $arguments];
            }
        }

        return ['Notfound', 'index', []];
    }

    public function isMatching($pattern)
    {
        $url = $this->app->request->url();
        
        return preg_match($pattern, $url);  
    }

    private function isMatchingRequestMethod($method)
    {
        $methods = ['GET', 'POST'];

        if (($method == 'both') && in_array($this->app->request->method(), $methods)) return true;

        if (is_array($method)) {

            if (count($method) == 1) {

                $method = $method[0];

            } else if (count($method) == 2) {
                
                if (in_array($method[0], $methods) && in_array($method[1], $methods)) return true;

            } else {

                return false;
            }
        }

        return $this->app->request->method() == $method;
    }

    public function getArgumentsFor($pattern)
    {
        $url = $this->app->request->url();
        
        preg_match($pattern, $url, $matches);

        array_shift($matches);

        return $matches;
    }

    public function getCurrent($key)
    {
        return $this->current[$key];
    }

    public function middleware($class)
    {
        return $this->app->load->middleware($class)->handle();
    }
}
