<?php

namespace System\Http\Middelwares;

use System\Middleware as Middleware;

class AjaxMiddelware extends Middleware
{
    public function handle()
    {   
        if(empty($_SERVER['HTTP_X_REQUESTED_WITH'])) $this->url->redirectTo('error');
    }
}