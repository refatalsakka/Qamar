<?php

namespace app\Middlewares;

use System\Application;
use app\Middlewares\MiddlewareIntrerface\MiddlewaresInterface as Middleware;

class AuthenticateMiddleware implements Middleware
{
  public function handle(Application $app, $next)
  {
    return $next;
  }
}
