<?php

namespace App\Middleware\Admin;

use System\Application;
use App\Middleware\MiddlewaresInterface as Middleware;

class AuthenticateMiddleware implements Middleware
{
    public function handle(Application $app, $next)
    {
        $request = $app->request->url();
        
        $login = $app->load->model('Login');

        $pagesWhenLogout = [
            '/login',
            '/login/submit',
            '/registration',
            '/registration/submit'
        ];

        if ($login->isLogged()) {
            
            if (in_array($request, $pagesWhenLogout)) $app->url->redirectTo('/');

        } else {

            if (! in_array($request, $pagesWhenLogout)) $app->url->redirectTo('/');
        }

        return $next;
    }
}