<?php

namespace System;

use Closure;
use Exception;
use Whoops\Run AS Whoops;
use Whoops\Util\Misc AS WhoopsMisc;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;

class Application
{    
    private $container = [];
 
    private static $instance;
    
    public function __construct(File $file)
    {
        // $this->handleErrors();

        $this->share('file', $file);

        $this->share('config', $this->file->call($this->file->to('config', '.php')));

        $this->loadHelpers();
    }

    private function handleErrors() {

        $run = new Whoops();
  
        $run->prependHandler(new PrettyPageHandler());

        if (WhoopsMisc::isAjaxRequest()) {

            $jsonHandler = new JsonResponseHandler();

            $jsonHandler->setJsonApi(true);

            $run->prependHandler($jsonHandler);
        }
        
        $run->register();
    }

    public static function getInstance($file = null)
    {
        return static::$instance = is_null(static::$instance) ? new static($file) : static::$instance;
    }

    public function run()
    {
        $this->session->start();

        $this->request->prepareUrl();

        $routes = glob("routes/*.php");

        foreach ($routes AS $route) $this->file->call($this->file->to($route));

        $output = $this->route->getProperRoute();

        $this->response->setOutput($output);

        $this->response->send();
    }

    private function loadHelpers()
    {
        $helpers = $this->file->to('Core/helpers', '.php');
        
        $this->file->call($helpers);
    }

    public function coreClasses()
    {
        return $this->config['alias'];
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
                throw new Exception("$key is not found");
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