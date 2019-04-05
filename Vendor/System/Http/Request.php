<?php

namespace System\Http;

class Request
{
    private $url;

    public function prepareUrl()
    {
        echo $_SERVER['REQUEST_URI'];
    }
}