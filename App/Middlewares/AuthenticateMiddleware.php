<?php

namespace app\Middlewares;

use System\Application;
use app\Middlewares\MiddlewareIntrerface\MiddlewaresInterface as Middleware;

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
      '/registration/submit',
      '/admin/login',
      '/admin/submit',
    ];

    if ($login->isLogged()) {
      if (in_array($request, $pagesWhenLogout)) {
        return $app->url->redirectTo('/admin');
      }
    } else {
      if (!in_array($request, $pagesWhenLogout)) {
        return $app->url->redirectTo('/admin/login');
      }
    }
    return $next;
  }
}
