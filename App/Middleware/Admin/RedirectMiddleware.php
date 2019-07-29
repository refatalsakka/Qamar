<?php

namespace App\Middleware\Admin;

use System\Application;
use App\Middleware\MiddlewaresInterface as Middleware;

class RedirectMiddleware implements Middleware
{
    public function handle()
    {

       if (! $this->session->has('error') || $this->session->get('error') != true) {
           
            $this->url->redirectTo('/');

       } else {

           $this->session->remove('error');
       }
    }
}