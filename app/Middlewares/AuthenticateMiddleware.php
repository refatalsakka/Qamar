<?php

namespace app\Middlewares;

use app\Middlewares\MiddlewareIntrerface\MiddlewaresInterface;

class AuthenticateMiddleware implements MiddlewaresInterface
{
    public function handle()
    {
        return true;
    }
}
