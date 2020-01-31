<?php

namespace App\Middlewares\User;

use System\Application;
use App\Middlewares\MiddlewareIntrerface\MiddlewaresInterface as Middleware;

class AddMiddleware implements Middleware
{
  public function handle(Application $app, $next)
  {
    $posts = $app->request->posts();
    $names = array_keys($posts);
    $allows = $app->file->call('config/admin/users/pages/add.php');

    if (!array_equal($names, $allows)) {
      return $app->url->redirectTo('404');
    }
    return $next;
  }
}
