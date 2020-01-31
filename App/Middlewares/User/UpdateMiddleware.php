<?php

namespace App\Middlewares\User;

use System\Application;
use App\Middlewares\MiddlewareIntrerface\MiddlewaresInterface as Middleware;

class UpdateMiddleware implements Middleware
{
  public function handle(Application $app, $next)
  {
    $posts = $app->request->posts();
    $name = array_keys($posts)[0];
    $allows = $app->file->call('config/admin/users/pages/update.php');

    if (!in_array($name, $allows)) {
      return $app->url->redirectTo('404');
    }
    return $next;
  }
}
