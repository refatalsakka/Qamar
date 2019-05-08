<?php

namespace System;

class Loader
{
    private $app;

    private $controllers = [];

    private $models = [];

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function action($controller, $method, array $arguments)
    {
        $object = $this->controller($controller);
        
        return call_user_func([$object, $method], $arguments);
    }

    public function controller($controller)
    {
        $controller = $this->getControllerName($controller);
        
        if (! $this->hasController($controller)) {
            $this->addController($controller);
        }
        
        return $this->getController($controller);
    } 

    private function hasController($controller)
    {
        return array_key_exists($controller, $this->controllers);
    }

    private function addController($controller)
    {
        $object = new $controller($this->app);

        $this->controllers[$controller] = $object;
    }

    private function getController($controller)
    {
        return $this->controllers[$controller];
    }

    private function getControllerName($controller)
    {
        $controller .= strpos($controller, 'Controller') ? '' : 'Controller';

        $controller = str_replace('/', '\\', $controller);

        $controller = 'App\\Controllers\\' . $controller;

        return $controller;
    }

    public function model($model)
    {
        $model = $this->getModelName($model);
        
        if (! $this->hasModel($model)) {
            $this->addModel($model);
        }
        
        return $this->getModel($model);
    } 

    private function hasModel($model)
    {
        return array_key_exists($model, $this->models);
    }

    private function addModel($model)
    {
        $object = new $model($this->app);

        $this->models[$model] = $object;
    }

    private function getModel($model)
    {
        return $this->models[$model];
    }

    private function getModelName($model)
    {
        $model .= strpos($model, 'Model') ? '' : 'Model';

        $model = str_replace('/', '\\', $model);

        $model = 'App\\Models\\' . $model;

        return $model;
    }
}