<?php

namespace System\Http;

use System\Application;

class Request
{
    private $app;

    private $url;

    private $baseUrl;

    private $files = [];

    private $link;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function prepareUrl()
    {
        $script = dirname($this->server('SCRIPT_NAME'));

        $requestUri = $this->server('REQUEST_URI');

        if (strpos($requestUri, '?')) list($requestUri, $queryString) = explode('?', $requestUri);
      
        if (! in_array($script, ['/', '\\'])) {
          
            $this->url = preg_replace('#^' . $script . '#', '', $requestUri);
        } else {
            
            $this->url = $requestUri;
        }

        if ($this->url !== '/') $this->url = rtrim($this->url, '/');
        
        $this->link = $this->server('REQUEST_SCHEME') . '://' . $this->server('HTTP_HOST');

        $this->baseUrl = $this->link . $requestUri;

    }

    public function get($key)
    {
        return array_get($_GET, $key);
    }

    public function post($key)
    {   
        return array_get($_POST, $key);
    }
    
    public function file($input)
    {
        if (isset($this->files[$input])) {

            return $this->files[$input];
        }
        
        $upoadedFile = new UploadeFile($this->app, $input);
        
        $this->files[$input] = $upoadedFile;

        return $this->files[$input];
    }

    public function server($key)
    {
        return array_get($_SERVER, $key);
    }

    public function method()
    {
        return $this->server('REQUEST_METHOD');
    }

    public function referer()
    {
        return $this->server('HTTP_REFERER');
    }

    public function baseUrl()
    {
        return $this->baseUrl;
    }

    public function url()
    {
        return $this->url;
    }

    public function link()
    {
        return $this->link;
    }
}