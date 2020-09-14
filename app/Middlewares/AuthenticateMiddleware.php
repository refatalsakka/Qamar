<?php

namespace app\Middlewares;

use app\Middlewares\MiddlewareIntrerface\MiddlewaresInterface;
use System\Controller as Middleware;

class AuthenticateMiddleware extends Middleware implements MiddlewaresInterface
{
  public function handle()
  {
    return true;
  }
}
