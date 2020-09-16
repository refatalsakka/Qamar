<?php

namespace app\Middlewares;

use app\Middlewares\MiddlewareIntrerface\MiddlewaresInterface;

/**
 * Ajax Middleware
 *
 * @property object $url
 */
class AjaxMiddleware implements MiddlewaresInterface
{
    public function handle()
    {
        if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            return $this->url->redirectTo('404');
        }
        return true;
    }
}
