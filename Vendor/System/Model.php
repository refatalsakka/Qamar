<?php

namespace System;

abstract class Model
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function __get($key)
    {
        return $this->app->get($key);    
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->app->db, $method], $args);
    }
}