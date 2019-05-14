<?php

namespace System;

class Route
{
    private $app;

    private $routes = [];

    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    public function add($url, $action, $requestMethos = 'GET')
    {
        $routes = [
            'url'       => $url,
            'pattern'   => $this->generatePattern($url),
            'action'    => $this->getAction($action),
            'method'    => $requestMethos
        ];

        $this->routes[] = $routes;
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

            if ($this->isMatching($route['pattern'])  && $this->isMatchingRequestMethod($route['method'])) {

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
        return $this->app->request->method() == $method;
    }

    public function getArgumentsFor($pattern)
    {
        $url = $this->app->request->url();
        
        preg_match($pattern, $url, $matches);

        array_shift($matches);

        return $matches;
    }
}
