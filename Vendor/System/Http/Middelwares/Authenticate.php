<?php

namespace System\Http\Middelwares;

use System\Middleware as Middleware;

class Authenticate extends Middleware
{
    public function index()
    {
       if (!$this->session->has('login') || $this->session->get('login') == false) {
           echo 'no';
       }
    }
}