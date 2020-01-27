<?php

namespace App\Middlewares;

use System\Application;
use App\Middlewares\MiddlewareIntrerface\MiddlewaresInterface as Middleware;

class AjaxMiddleware implements Middleware
{
  public function handle(Application $app, $next)
  {
    if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      return $app->url->redirectTo('404');
    }
    return $next;
  }
}
