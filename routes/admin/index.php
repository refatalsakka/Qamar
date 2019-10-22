<?php

$app = app();

// Rotes admins

$adminOptions = [
  'prefix' => '/admin',
  'controller' => 'Admin',
  'middleware' => ['auth']
];

$app->route->group($adminOptions, function($route) {

  // Not found
  $route->add('/404', 'Notfound', 'GET');

  $route->add('/', 'Home', 'GET');

  //Login
  $route->add('/login', 'Login', 'GET');
  $route->add('/submit', 'Login@submit', 'POST');
  $route->add('/logout', 'Logout');

  //Settings
  $route->add('/settings', 'Settings');

  //Users
  $route->package('/users', 'Users', [
    'add' => [
      'ajax',
    ],
    'update' => [
      'ajax',
    ],
  ]);

  // Profile
  $route->add('/profile', 'Profile', 'GET');
});
