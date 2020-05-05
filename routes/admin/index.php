<?php

$app = app();

if (!$app->request->isRequestToAdminManagement()) return;

// Rotes admins

$adminOptions = [
  'prefix' => '/admin',
  'controller' => 'Admin',
  'middleware' => ['auth', 'permissions']
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
  $route->package('/users', 'User\User', [
    'add' => [
      'ajax',
      'userAdd',
    ],
    'update' => [
      'ajax',
      'userUpdate',
    ],
  ]);
  $route->add('/users/filter', 'User\User@search', 'GET');

  // Profile
  $route->add('/profile', 'Profile', 'GET');
});
