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
  $route->add('/submit', 'Login@submit', 'POST', ['ajax']);
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


  // $route->add('/profile', 'Profile', 'GET');
  // $route->add('/profile/update', 'Profile', 'POST', 'ajax');
});

// $profileOtions = [
//   'prefix' => '/admin/profile',
//   'controller' => 'Admin',
//   'middleware' => ['auth']
// ];

// //Profile
// $app->route->group($profileOtions, function($route) {

// });
