<?php

namespace System\Http\Middlewares;

use System\Middleware as Middleware;

class RedirectMiddleware extends Middleware
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