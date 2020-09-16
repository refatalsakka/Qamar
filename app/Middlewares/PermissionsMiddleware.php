<?php

namespace app\Middlewares;

use app\Middlewares\MiddlewareIntrerface\MiddlewaresInterface;

/**
 * Permissions Middleware
 *
 */
class PermissionsMiddleware implements MiddlewaresInterface
{
    public function handle()
    {
        return true;
    }
}
