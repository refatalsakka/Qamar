<?php

namespace System;

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

        $this->loader->action($controller, $method, $arguments);
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
        $this->file->call($this->file->to('Vendor/helpers', '.php'));
    }

    public function coreClasses()
    {
        return [
            'request'   =>  'System\\Http\\Request',
            'response'  =>  'System\\Http\\Response',
            'route'     =>  'System\\route',
            'session'   =>  'System\\Session',
            'cookie'    =>  'System\\Cookie',
            'loader'    =>  'System\\Loader',
            'html'      =>  'System\\Html',
            'db'        =>  'System\\Database',
            'view'      =>  'System\\View\\ViewFactory',
        ];
    }

    public function share($key, $value)
    {
        $this->container[$key] = $value;
    }

    public function get($key)
    {
        if (! $this->isSharing($key)) {
            if ($this->isCoreAlias($key)) {

                $this->share($key, $this->createObject($key));
                
            } else {
                die('Ohh! <strong>' . $key .'</strong> is not found');
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