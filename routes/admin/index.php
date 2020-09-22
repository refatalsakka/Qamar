<?php

$app = app();

$adminOptions = [
  'prefix' => '/admin',
  'controller' => 'Admin',
  'middleware' => ['Authenticate', 'Permissions']
];

$app->route->group($adminOptions, function ($route) {
    $route->get('/', 'Home');
    $route->get('/home', 'Home');
});
