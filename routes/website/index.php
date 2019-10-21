<?php

$app = app();

// $app->route->add() to add to the Routes
// $app->pages->add() to add to the Admin Panel

$app->route->add('/', 'Website/Home');
$app->route->add('/home', 'Website/Home', 'GET');
$app->pages->add('home', '/home', 'icon-home');

// Services
$servicesOptions = [
  'prefix' => '/services',
  'controller' => 'Website',
  'middleware' => [],
  'title' => 'services',
  'icon' => 'icon-support'
];

$app->route->group($servicesOptions, function($route) {
  $route->add('/', 'Services');
});

$app->pages->group($servicesOptions, function($route) {
  $route->add('main', '/services');
});

// Contact
$app->route->add('/contact', 'Website/Contact');
$app->pages->add('contact', '/contact', 'icon-envelope');

//Data Protection
$app->route->add('/privacy', 'Website/privacy');
$app->pages->add('privacy', '/privacy', 'icon-shield');

//Imprint
$app->route->add('/imprint', 'Website/Imprint');
$app->pages->add('imprint', '/imprint', 'icon-info');

// Not found
$app->route->add('/404', 'Website/Notfound', 'GET');
$app->pages->add('not found', '/404', 'fas fa-times');
