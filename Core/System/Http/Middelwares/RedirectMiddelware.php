<?php

namespace System\Http\Middelwares;

use System\Middleware as Middleware;

class RedirectMiddelware extends Middleware
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