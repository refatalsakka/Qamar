<?php

return [
    'classes' => [
        'request'   =>  'System\\Http\\Request',
        'response'  =>  'System\\Http\\Response',
        'route'     =>  'System\\Route',
        'session'   =>  'System\\Session',
        'cookie'    =>  'System\\Cookie',
        'load'      =>  'System\\Loader',
        'html'      =>  'System\\Html',
        'db'        =>  'System\\Database',
        'url'       =>  'System\\Url',
        'validator' =>  'System\\Validation',
        'paginatio' =>  'System\\Paginatio',
        'view'      =>  'System\\View\\ViewFactory',
    ],
    'middlewares' => [
        'admin' => [
            'auth'        => 'App\\Middleware\\Admin\\AuthenticateMiddleware',
            'ajax'        => 'App\\Middleware\\Admin\\AjaxMiddleware',
            'permissions' => 'App\\Middleware\\Admin\\PermissionsMiddleware',
            'redirect'    => 'App\\Middleware\\Admin\\RedirectMiddleware',
        ],
        'website' => [
        ]
    ]
];