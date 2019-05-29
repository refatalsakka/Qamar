<?php

namespace System\Http\Middelwares;

use System\Middleware as Middleware;

class Authenticate extends Middleware
{
    public function handle()
    {   
        $request = $this->request->url();
        
        $login = $this->load->model('Login');

        // if ($request == '/admin/login') {

        //     if ($login->isLogged()) $this->url->redirectTo('/admin');

        // } else {

        //     if (! $login->isLogged()) $this->url->redirectTo('/admin/login');
        // }
    }
}