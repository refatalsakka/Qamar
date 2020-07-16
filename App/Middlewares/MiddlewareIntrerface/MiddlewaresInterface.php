<?php

namespace app\Middlewares\MiddlewareIntrerface;

use System\Application;

interface MiddlewaresInterface
{
  public function handle(Application $app, $nex);
}
