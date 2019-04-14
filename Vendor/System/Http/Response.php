<?php

namespace System\Http;

use System\Application;

class Response
{
    private $app;

    private $headers = [];

    private $content = '';

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function setOutput($content)
    {
        $this->content = $content;
    }

    public function setHeaders($header, $value)
    {
        $this->headers[$header] = $value;
    }
    
    public function sendOutput()
    {
        echo $this->content;
    }

    public function sendHeaders()
    {
        foreach($this->headers as $header => $value)
        {
            header($header . ':' . $value);
        }
    }
    
    public function send()
    {
        $this->sendHeaders();

        $this->sendOutput();
    }
}