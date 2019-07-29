<?php

namespace App\Middleware;

use System\Application;

interface MiddlewaresInterface
{
    public function handle(Application $app, $nex);
}