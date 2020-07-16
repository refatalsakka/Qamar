<?php

namespace app\Middlewares;

use System\Application;
use app\Middlewares\MiddlewareIntrerface\MiddlewaresInterface as Middleware;

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
