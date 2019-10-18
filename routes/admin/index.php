<?php

$app = app();

// Rotes admins

$adminOptions = [
  'prefix' => '/admin',
  'controller' => 'Admin',
  'middleware' => ['auth']
];

$app->route->group($adminOptions, function($route) {

  $route->add('/', 'Home', 'GET');

  //Login
  $route->add('/login', 'Login', 'GET');
  $route->add('/submit', 'Login@submit', 'POST', ['ajax']);
  $route->add('/logout', 'Logout');

  //Profile
  $route->add('/profile', 'Profile');

  //Settings
  $route->add('/settings', 'Settings');

  //users
  $route->package('/users', 'Users', [
    'add' => [
      // 'ajax',
    ],
    'update' => [
      'ajax',
    ],
  ]);
});
