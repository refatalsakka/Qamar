<?php

namespace System;

class Application
{

    private $container = [];

    public function __construct(File $file)
    {
        $this->share('file', $file);

        $this->registerClasses();

        $this->loadHelpers();
    }

    public function run()
    {
        $this->session->start();

        $this->request->prepareUrl();
    }

    public function registerClasses()
    {
        spl_autoload_register([$this, 'load']);
    }

    public function load($class)
    {
        $toCorrect = (strpos($class, 'App') === 0) ? 'to': 'toVendor';

        $file = $this->file->$toCorrect($class, '.php');

        $this->file->call($file);
    }

    private function loadHelpers()
    {
        $this->file->call($this->file->toVendor('helpers', '.php'));
    }

    public function coreClasses()
    {
        return [
            'request'   =>  'System\\Http\\Request',
            'response'  =>  'System\\Http\\Response',
            'session'   =>  'System\\Session',
            'cookie'    =>  'System\\Cookie',
            'load'      =>  'System\\Load',
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