<?php

namespace System;

class Loader
{
    /**
     * Application Object
     *
     * @var \System\Application
     */
    private $app;

    /**
     * Controllers container
     *
     * @var array
     */
    private $controllers = [];

    /**
     * Models container
     *
     * @var array
     */
    private $models = [];

    /**
     * Models container
     *
     * @var array
     */
    private $middlewares = [];

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
     * Call the given controller with the given method
     * and pass the given arguments to the controller method
     *
     * @param string $controller
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function action($controller, $method, array $arguments)
    {
        $object = $this->controller($controller);

        return call_user_func([$object, $method], $arguments);
    }

    /**
     * Call the given controller
     *
     * @param string $controller
     * @return object
     */
    public function controller($controller)
    {
        $controller = $this->getControllerName($controller);

        if (!$this->hasController($controller)) {
            $this->addController($controller);
        }
        return $this->getController($controller);
    }

    /**
     * Determine if the given class|controller exists
     * in the controllers container
     *
     * @param string $controller
     * @return bool
     */
    private function hasController($controller)
    {
        return array_key_exists($controller, $this->controllers);
    }

    /**
     * Create new object for the given controller and store it
     * in controllers container
     *
     * @param string $controller
     * @return void
     */
    private function addController($controller)
    {
        $object = new $controller($this->app);

        $this->controllers[$controller] = $object;
    }

    /**
     * Get the controller object
     *
     * @param string $controller
     * @return object
     */
    private function getController($controller)
    {
        return $this->controllers[$controller];
    }


    /**
     * Get the full class name for the given controller
     *
     * @param string $controller
     * @return string
     */
    private function getControllerName($controller)
    {
        $controller .= strpos($controller, 'Controller') ? '' : 'Controller';

        return 'app\\Controllers\\' . $controller;
    }

    /**
     * Call the given model
     *
     * @param string $model
     * @return object
     */
    public function model($model)
    {
        $model = $this->getModelName($model);

        if (!$this->hasModel($model)) {
            $this->addModel($model);
        }
        return $this->getModel($model);
    }

    /**
     * Determine if the given class|model exists
     * in the models container
     *
     * @param string $model
     * @return bool
     */
    private function hasModel($model)
    {
        return array_key_exists($model, $this->models);
    }

    /**
     * Create new object for the given model and store it
     * in models container
     *
     * @param string $model
     * @return void
     */
    private function addModel($model)
    {
        $object = new $model($this->app);

        $this->models[$model] = $object;
    }

    /**
     * Get the model object
     *
     * @param string $model
     * @return object
     */
    private function getModel($model)
    {
        return $this->models[$model];
    }

    /**
     * Get the full class name for the given model
     *
     * @param string $model
     * @return string
     */
    private function getModelName($model)
    {
        $model .= strpos($model, 'Model') ? '' : 'Model';

        return 'app\\Models\\' . $model;
    }

    /**
     * Call the given middleware
     *
     * @param string $middleware
     * @return object
     */
    public function middleware($middleware)
    {
        $middleware = $this->getMiddlewareName($middleware);

        if (!$this->hasMiddleware($middleware)) {
            $this->addMiddleware($middleware);
        }
        return $this->getMiddleware($middleware);
    }

    /**
     * Determine if the given class|middleware exists
     * in the middlewares container
     *
     * @param string $middleware
     * @return bool
     */
    private function hasMiddleware($middleware)
    {
        return array_key_exists($middleware, $this->middlewares);
    }

    /**
     * Create new object for the given middleware and store it
     * in middlewares container
     *
     * @param string $middleware
     * @return void
     */
    private function addMiddleware($middleware)
    {
        $object = new $middleware($this->app);

        $this->middlewares[$middleware] = $object;
    }

    /**
     * Get the middleware object
     *
     * @param string $middleware
     * @return object
     */
    private function getMiddleware($middleware)
    {
        return $this->middlewares[$middleware];
    }

    /**
     * Get the full class name for the given middleware
     *
     * @param string $middleware
     * @return string
     */
    private function getMiddlewareName($middleware)
    {
        $middleware .= strpos($middleware, 'Middleware') ? '' : 'Middleware';

        return 'app\\Middlewares\\' . $middleware;
    }
}
