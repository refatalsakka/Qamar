<?php

namespace System\Http\Middlewares;

use System\Middleware as Middleware;

class AjaxMiddleware extends Middleware
{
    public function handle()
    {   
        if(empty($_SERVER['HTTP_X_REQUESTED_WITH'])) $this->url->redirectTo('error');
    }
}