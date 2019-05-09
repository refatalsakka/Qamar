<?php

namespace System;

class Url
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function link($path)
    {
        return $this->app->request->baseUrl() . trim($path, '/');
    }

    public function redirectTo($path)
    {
        header('location:' . $this->link($path));
        exit;
    }  
}