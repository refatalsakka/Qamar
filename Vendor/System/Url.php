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
        $link = $this->app->request->link();
        
        $path = rtrim($path, '/');
        $path = ltrim($path, '/');

        return $link . '/' . $path;
    }

    public function redirectTo($path)
    {
        header('location:' . $this->link($path));
        exit;
    }  
}