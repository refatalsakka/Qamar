<?php

namespace System;

abstract class Controller
{
    /**
     * Application Object
     *
     * @var \System\Application
     */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Call shared Application Objects dynamically
     *
     * @param string $key
     * @return object
     */
    public function __get($key)
    {
        return $this->app->get($key);
    }
}
