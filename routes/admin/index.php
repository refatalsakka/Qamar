<?php

$app = app();

$adminOptions = [
  'prefix' => '/admin',
  'controller' => 'Admin',
  'middleware' => ['Authenticate', 'Permissions']
];

$app->route->group($adminOptions, function ($route) {
    $route->add('/', 'Home');
    $route->add('/home', 'Home');
});
