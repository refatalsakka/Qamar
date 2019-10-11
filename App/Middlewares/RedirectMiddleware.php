<?php

namespace App\Middlewares;

use System\Application;
use App\Middlewares\MiddlewareIntrerface\MiddlewaresInterface as Middleware;

class RedirectMiddleware implements Middleware
{
  public function handle(Application $app, $next)
  {

    if (!$this->session->has('error') || $this->session->get('error') != true) {

      return $this->url->redirectTo('/');

    } else {

      $this->session->remove('error');
    }

    return $next;
  }
}
