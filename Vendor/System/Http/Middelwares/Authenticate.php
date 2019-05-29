<?php

namespace System\Http\Middelwares;

use System\Middleware as Middleware;

class Authenticate extends Middleware
{
    public function handle()
    {   
        $request = $this->request->url();
        
        $login = $this->load->model('Login');

        if (strpos($request, '/admin') === 0) {

            if (! $login->isLogged()) $this->url->redirectTo('/login');
            
        } else {
            
            if ($login->isLogged()) $this->url->redirectTo('/home');
        }
    }
}