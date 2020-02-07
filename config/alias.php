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
    'validator' =>  'System\\Validation\\Validator',
    'paginatio' =>  'System\\Paginatio',
    'view'      =>  'System\\View',
    'pages'     =>  'System\\Pages',
    'hash'      =>  'System\\Hash',
  ],
  'middlewares' => [
    'auth'        => 'App\\Middlewares\\AuthenticateMiddleware',
    'ajax'        => 'App\\Middlewares\\AjaxMiddleware',
    'permissions' => 'App\\Middlewares\\PermissionsMiddleware',
    'userAdd'     => 'App\\Middlewares\\User\\AddMiddleware',
    'userUpdate'  => 'App\\Middlewares\\User\\UpdateMiddleware',
  ]
];
