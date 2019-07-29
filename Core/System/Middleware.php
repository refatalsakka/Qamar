<?php

namespace System;

abstract class Middleware
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
}