<?php

namespace System;

class Route
{
    /**
     * Application Object
     *
     * @var \System\Application
     */
    private $app;

    /**
     * Routes
     *
     * @var array
     */
    private $routes = [];

    /**
     * Current route
     *
     * @var array
     */
    public $current = [];

    /**
     * Prefix
     *
     * @var string
     */
    private $prefix;

    /**
     * Controller
     *
     * @var string
     */
    private $basController;

    /**
     * Middleware
     *
     * @var string
     */
    private $middleware = [];

    /**
     * Constructor
     *
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Add route to $routes after processing each parameter
     *
     * @param string $url
     * @param string $action
     * @param string|array $requestMethods
     * @param string|array $middleware
     * @return object $this
     */
    public function add($url, $action, $requestMethods = ['GET'], $middleware = [])
    {
        $url = $this->setPrefix($url);
        $action = $this->setAction($action);
        $middleware = $this->setMiddleware($middleware);
        $routes = [
            'url' => $url,
            'pattern' => $this->generatePattern($url),
            'action' => $this->getAction($action),
            'method' => $requestMethods,
            'middleware' => $middleware
        ];

        $this->routes[] = $routes;

        return $this;
    }

    /**
     * Add route GET to $routes
     *
     * @param string $url
     * @param string $action
     * @param string|array $middleware
     * @return object $this
     */
    public function get($url, $action, $middleware = [])
    {
        $this->add($url, $action, ['GET'], $middleware);
        return $this;
    }

    /**
     * Add route POST to $routes
     *
     * @param string $url
     * @param string $action
     * @param string|array $middleware
     * @return object $this
     */
    public function post($url, $action, $middleware = [])
    {
        $this->add($url, $action, ['POST'], $middleware);
        return $this;
    }

    /**
     * Add prefix at the first of $url if exists or not equal to '/'
     *
     * @param string $url
     * @return string $url
     */
    private function setPrefix($url)
    {
        if ($this->prefix && $this->prefix !== '/') {
            $url = $this->prefix . $url;
            $url = rtrim($url, '/');
        }
        return $url;
    }

    /**
     * Add basController at the first of $action if exists
     *
     * @param string $action
     * @return string $action
     */
    private function setAction($action)
    {
        if ($this->basController) {
            $action = $this->basController . '/' . $action;
        }
        return $action;
    }

    /**
     * Merge the given middleware if exists
     *
     * @param string $middleware
     * @return array
     */
    private function setMiddleware($middleware)
    {
        if (!is_array($middleware)) {
            $middleware = [$middleware];
        }
        return array_merge($this->middleware, $middleware);
    }

    /**
     * Clean the given path to the right controller and method
     *
     * @param string $action
     * @return array $action
     */
    private function getAction($action)
    {
        $action = str_replace('/', '\\', $action);
        $action = (strpos($action, '@') != 0) ? $action : $action . '@index';
        $action = explode('@', $action);

        return $action;
    }

    /**
     * generate a pattern using regex
     *
     * @param string $url
     * @return string $pattern
     */
    private function generatePattern($url)
    {
        $pattern = '#^';
        $pattern .= str_replace([':text', ':id'], ['([a-zA-Z0-9-]+)', '(\d+)'], strtolower($url));
        $pattern .= '$#';

        return $pattern;
    }

    /**
     * Check if the both methods are true
     *
     * @param string $pattern
     * @param string $methods
     * @return bool
     */
    private function fullMatch($pattern, $methods)
    {
        return $this->isMatchingPattern($pattern) && $this->app->request->isMatchingRequestMethod($methods);
    }

    /**
     * Check if the url of the requesting page is matching the given pattern
     *
     * @param string $pattern
     * @return bool
     */
    private function isMatchingPattern($pattern)
    {
        $url = strtolower($this->app->request->url());

        return preg_match($pattern, $url);
    }

    /**
     * Get the rest parameter of the url as paramter for
     * the method
     *
     * @param string $pattern
     * @return bool
     */
    private function getArgumentsFor($pattern)
    {
        $url = $this->app->request->url();

        preg_match($pattern, $url, $matches);

        array_shift($matches);

        return $matches;
    }

    /**
     * Group routes together
     *
     * @param array $groupOptions
     * @param callable $callback
     * @return object $this
     */
    public function group($groupOptions, callable $callback)
    {
        $prefix = $groupOptions['prefix'];
        $controller = $groupOptions['controller'];
        $middleware = $groupOptions['middleware'];

        $this->prefix = $prefix;
        $this->basController = $controller;
        $this->middleware = $middleware;

        $callback($this);

        $this->prefix = '';
        $this->basController = '';
        $this->middleware = [];

        return $this;
    }

    /**
     * Set a complate package
     *
     * @param string $url
     * @param string $controller
     * @param array $middlewares
     * @return object $this
     */
    public function package($url, $controller, $middlewares = [])
    {
        $this->add($url, $controller);
        $this->add("$url/:id", "$controller@row", ['GET'], $middlewares['row'] ?? []);
        $this->add("$url/new", "$controller@new", ['GET'], $middlewares['new'] ?? []);
        $this->add("$url/add", "$controller@add", ['POST'], $middlewares['add'] ?? []);
        $this->add("$url/update/:id", "$controller@update", ['POST'], $middlewares['update'] ?? []);
        $this->add("$url/delete/:id", "$controller@delete", ['POST'], $middlewares['delete'] ?? []);

        return $this;
    }

    /**
     * Loop over the routes and find the right one:
     * found: break the loop and check if the request can continue by calling the middleware
     *  can be continue: call the right page
     *  can't be continue: .....
     * not found: call the 404 page
     *
     * @return string
     */
    public function getProperRoute()
    {
        foreach ($this->routes as $route) {
            if ($this->fullMatch($route['pattern'], $route['method'])) {
                $this->current = $route;

                $continue = $this->app->request->canRequestContinue($route['middleware']);

                if ($continue) {
                    list($controller, $method) = $route['action'];

                    $arguments = $this->getArgumentsFor($route['pattern']);

                    return (string) $this->app->load->action($controller, $method, $arguments);
                }
                break;
            }
        }
        return notFoundPage();
    }

    /**
     * Get current route
     *
     * @return array $current
     */
    public function getCurrent()
    {
        return $this->current;
    }
}
