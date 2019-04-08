<?php

namespace System\Http;

class Request
{
    private $url;

    private $baseUrl;

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

        $this->baseUrl = $this->server('REQUEST_SCHEME') . '://' . $this->server('HTTP_HOST') . $requestUri . '/';
    }

    public function get($key)
    {
        return array_get($_GET, $key);
    }

    public function post($key)
    {
        return array_get($_GET, $key);
    }

    public function server($key)
    {
        return array_get($_SERVER, $key);
    }

    public function method()
    {
        return $this->server('REQUEST_METHOD');
    }

    public function baseUrl()
    {
        return $this->baseUrl;
    }

    public function url()
    {
        return $this->url;
    }
}