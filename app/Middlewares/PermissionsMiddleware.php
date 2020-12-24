<?php

namespace app\Middlewares;

use app\Middlewares\MiddlewareIntrerface\MiddlewaresInterface;

class PermissionsMiddleware implements MiddlewaresInterface
{
    /**
     * Permissions
     *
     */
    public function handle()
    {
        return true;
    }
}
