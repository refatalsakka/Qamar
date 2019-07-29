<?php

namespace System;

use Exception;

class Route
{
    const NEXT = '_NEXT_';

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
                
                $this->current = $route;

                $output = '';

                if ($route['middleware']) {
                  
                    if (is_array($route['middleware'])) {
                        
                        foreach($route['middleware'] as $middleware) {
                            
                            $output = $this->middleware($middleware);

                            if ($output != '') break;   
                        }
    
                    } else {
                    
                        $output = $this->middleware($route['middleware']);

                        if ($output != '') break;
                    }
                }
       
                if ($output == '') {

                    list($controller, $method) = $route['action'];

                    $arguments = $this->getArgumentsFor($route['pattern']);
              
                    $output = (string) $this->app->load->action($controller, $method, $arguments);

                    return $output;

                } else {
                    break;
                }
            }
        }

        $output = (string) $this->app->load->action('notFound', 'index', []);

        return $output;
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

    private function middleware($middleware, $from = 'admin')
    {
        $middlewareInterface = 'App\\Middleware\\MiddlewaresInterface';
        
        $middlewares = $this->app->config['middlewares'][$from];

        $middlewareClass = $middlewares[$middleware];
   
        if (! in_array($middlewareInterface, class_implements($middlewareClass))) {
            throw new Exception("$middlewareClass not Implement");
        }

        $middlewareObject = new $middlewareClass;

        $output = $middlewareObject->handle($this->app, static::NEXT);

        if ($output && $output === static::NEXT) $output = '';

        return $output;
    }
}
