<?php

namespace System;

use Exception;

class Route
{
  const NEXT = '_NEXT_';

  private $app;

  private $routes = [];

  public $current = [];

  private $prefix;

  private $basController;

  private $groupMiddleware = [];

  public function __construct(Application $app)
  {
    $this->app = $app;
  }

  public function add($url, $action, $requestMethos = 'GET', $middleware = [])
  {
    $url = $this->setPrefix($url);

    $action = $this->setAction($action);

    $middleware = $this->setMiddleware($middleware);

    $routes = [
      'url'         => $url,
      'pattern'     => $this->generatePattern($url),
      'action'      => $this->getAction($action),
      'method'      => $requestMethos,
      'middleware'  => $middleware
    ];

    $this->routes[] = $routes;

    return $this;
  }

  private function setPrefix($url)
  {
    if ($this->prefix && $this->prefix !== '/') {

      $url = $this->prefix . $url;

      $url = rtrim($url, '/');
    }

    return $url;
  }

  private function setAction($action)
  {
    if ($this->basController) {

      $action = $this->basController . '/' . $action;
    }

    return $action;
  }

  private function setMiddleware($middleware)
  {
    if (!is_array($middleware)) {

      $middleware = [$middleware];
    }

    $middleware = array_merge($this->groupMiddleware, $middleware);

    return $middleware;
  }

  public function group($groupOptions, callable $callback)
  {
    $prefix = $groupOptions['prefix'];
    $controller = $groupOptions['controller'];
    $middleware = $groupOptions['middleware'];

    $url = $this->app->request->url();

    if (($this->prefix && $prefix !== $this->prefix) || ($prefix && strpos($url, $prefix) !== 0)) {

      return $this;
    }

    $this->prefix = $prefix;
    $this->basController = $controller;
    $this->groupMiddleware = $middleware;

    $callback($this);

    return $this;
  }

  public function package($url, $controller, $middlewares = [])
  {
    $this->add("$url", "$controller");

    $row = isset($middlewares['row']) ? $middlewares['row'] : [];
    $this->add("$url/:id", "$controller@row", 'GET', $row);

    $new = isset($middlewares['new']) ? $middlewares['new'] : [];
    $this->add("$url/new", "$controller@new", 'GET', $new);

    $add = isset($middlewares['add']) ? $middlewares['add'] : [];
    $this->add("$url/add", "$controller@add", 'POST', $add);

    $update = isset($middlewares['update']) ? $middlewares['update'] : [];
    $this->add("$url/update/:id", "$controller@update", 'POST', $update);

    $delete = isset($middlewares['delete']) ? $middlewares['delete'] : [];
    $this->add("$url/delete/:id", "$controller@delete", 'POST', $delete);

    return $this;
  }

  public function generatePattern($url)
  {
    $pattern = '#^';
    $pattern .= str_replace([':text', ':id'], ['([a-zA-Z0-9-]+)', '(\d+)'], strtolower($url));
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
    foreach ($this->routes as $route) {

      if ($this->isMatching($route['pattern']) && $this->isMatchingRequestMethod($route['method'])) {

        $this->current = $route;

        $continue = $this->continue($route['middleware']);

        if ($continue == static::NEXT) {

          list($controller, $method) = $route['action'];

          $arguments = $this->getArgumentsFor($route['pattern']);

          $output = (string) $this->app->load->action($controller, $method, $arguments);

          return $output;
        }

        break;
      }
    }

    $notfound = 'Website\Notfound';

    if ($this->app->request->isRequestToAdminManagement() && $this->app->load->model('Login')->isLogged()) {

      $notfound = 'Admin\Notfound';
    }

    return (string) $this->app->load->action($notfound, 'index', []);
  }

  public function isMatching($pattern)
  {
    $url = $this->app->request->url();

    $url = strtolower($url);

    return preg_match($pattern, $url);
  }

  private function isMatchingRequestMethod($method)
  {
    $allowMethods = ['GET', 'POST'];

    if ($method == 'BOTH') {

      return $this->checkRequestMethodsBoth($allowMethods);
    }

    if (is_array($method)) {

      return $this->checkRequestMethodsArray($method, $allowMethods);
    }

    return $this->app->request->method() == $method;
  }

  private function checkRequestMethodsArray($methods = null, $allowMethods)
  {
    if (count($methods) == 1) {

      return $this->app->request->method() == $methods[0];

    } else {

      if (array_equal($methods, $allowMethods)) {

        return true;
      }

      return false;
    }
  }

  private function checkRequestMethodsBoth($allowMethods)
  {
    if (in_array($this->app->request->method(), $allowMethods)) {

      return true;
    }

    return false;
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

  private function middleware($middleware)
  {
    $middlewareInterface = 'App\Middlewares\MiddlewareIntrerface\MiddlewaresInterface';

    $middlewares = $this->app->alias['middlewares'];

    $middlewareClass = $middlewares[$middleware];

    if (!in_array($middlewareInterface, class_implements($middlewareClass))) {

      throw new Exception("$middlewareClass not Implement");
    }

    $middlewareObject = new $middlewareClass;

    $output = $middlewareObject->handle($this->app, static::NEXT);

    return $output;
  }

  private function continue($middlewares)
  {
    if (!empty($middlewares)) {

      foreach ($middlewares as $middleware) {

        $output = $this->middleware($middleware);

        if ($output !== static::NEXT) {

          return $output;
        }

      }
    }

    return static::NEXT;
  }
}
