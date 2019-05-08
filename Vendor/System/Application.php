<?php

namespace System;

use Closure;

class Application
{    
    private $container = [];
 
    private static $instance;
    
    public function __construct(File $file)
    {
        $this->share('file', $file);

        $this->registerClasses();

        $this->loadHelpers();
    }

    public static function getInstance($file = null)
    {
        return static::$instance = is_null(static::$instance) ? new static($file) : static::$instance;
    }

    public function run()
    {
        $this->session->start();

        $this->request->prepareUrl();

        $this->file->call($this->file->to('App/index', '.php'));

        list($controller, $method, $arguments) = $this->route->getProperRoute();

        $output = (string) $this->loader->action($controller, $method, $arguments);

        $this->response->setOutput($output);

        $this->response->send($output);

    }

    public function registerClasses()
    {
        spl_autoload_register([$this, 'load']);
    }

    public function load($class)
    {
        $toCorrect = (strpos($class, 'App') === 0) ? '': 'Vendor\\';

        $file = $this->file->to($toCorrect . $class, '.php');

        $this->file->call($file);
    }

    private function loadHelpers()
    {
        $helpers = $this->file->to('Vendor/helpers', '.php');
        
        $this->file->call($helpers);
    }

    public function coreClasses()
    {
        $alias = $this->file->to('config/alias', '.php');

        return $this->file->call($alias);
    }

    public function share($key, $value)
    {
        if ($value instanceof Closure) {

            $value = call_user_func($value, $this);
        }

        $this->container[$key] = $value;
    }

    public function get($key)
    {
        if (! $this->isSharing($key)) {
            if ($this->isCoreAlias($key)) {

                $this->share($key, $this->createObject($key));
                
            } else {
                die('Ohh! <strong>' . $key .'</strong> is not found');
                exit();
            }
        }
        return $this->container[$key];
    }

    public function isSharing($key)
    {
        return isset($this->container[$key]);
    }

    public function isCoreAlias($key)
    {
        return isset($this->coreClasses()[$key]);
    }

    public function createObject($key)
    {
        $object = $this->coreClasses()[$key];
       
        return new $object($this);
    }

    public function __get($key)
    {
        return $this->get($key);
    }
}