<?php

namespace app\Middlewares;

use app\Middlewares\MiddlewareIntrerface\MiddlewaresInterface;

/**
 * Authenticate Middleware
 *
 */
class AuthenticateMiddleware implements MiddlewaresInterface
{
    public function handle()
    {
        return true;
    }
}
