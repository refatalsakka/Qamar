<?php

namespace App\Middleware\Admin;

use System\Application;
use App\Middleware\MiddlewaresInterface as Middleware;

class AjaxMiddleware implements Middleware
{
    public function handle()
    {   
        if(empty($_SERVER['HTTP_X_REQUESTED_WITH'])) $this->url->redirectTo('error');
    }
}