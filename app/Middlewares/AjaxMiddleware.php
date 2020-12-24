<?php

namespace app\Middlewares;

use app\Middlewares\MiddlewareIntrerface\MiddlewaresInterface;

class AjaxMiddleware implements MiddlewaresInterface
{
    /**
     * Check if the request is by Ajax.
     *
     * @property object $url
     */
    public function handle()
    {
        if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            return $this->url->redirectTo('404');
        }
        return true;
    }
}
