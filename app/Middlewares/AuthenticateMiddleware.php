<?php

namespace app\Middlewares;

use app\Middlewares\MiddlewareIntrerface\MiddlewaresInterface;

class AuthenticateMiddleware implements MiddlewaresInterface
{
    /**
     * Authenticate
     *
     */
    public function handle()
    {
        return true;
    }
}
