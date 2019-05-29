<?php

namespace System;

class Cookie
{
    private $app;

    private $path = '/';

    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->path = dirname($this->app->request->server('SCRIPT_NAME')) ?: '/';
    }

    public function set($key, $value, $hours = 1800)
    {
        $expireTime = $hours == -1 ? -1 : time() + $hours * 3600;

        setcookie($key, $value, $expireTime, $this->path, '', false, true);
    }

    public function get($key , $default = null)
    {
        return array_get($_COOKIE, $key, $default);
    }

    public function has($key)
    {
        return array_key_exists($key, $_COOKIE);
    }

    public function remove($key)
    {
        $this->set($key, null, -1);

        unset($_COOKIE[$key]);
    }

    public function all()
    {
        return $_COOKIE;
    }

    public function destroy()
    {
        foreach (array_keys($this->all()) AS $key) {
            $this->remove($key);
        }

        unset($_COOKIE);
    }
}