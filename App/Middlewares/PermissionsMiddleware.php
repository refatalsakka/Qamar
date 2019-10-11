<?php

namespace App\Middlewares;

use System\Application;
use App\Middlewares\MiddlewareIntrerface\MiddlewaresInterface as Middleware;

class PermissionsMiddleware implements Middleware
{
  public function handle(Application $app, $next)
  {
    return $next;
  }
}
