<?php

return [
    'auth'        => 'System\\Http\\Middlewares\\AuthenticateMiddleware',
    'permissions' => 'System\\Http\\Middlewares\\PermissionsMiddleware',
    'ajax'        => 'System\\Http\\Middlewares\\AjaxMiddleware',
    'redirect'    => 'System\\Http\\Middlewares\\RedirectMiddleware',
];