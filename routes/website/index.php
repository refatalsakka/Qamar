<?php

$app = app();

// $app->route->add() to add to the Routes
// $app->pages->add() to add to the Admin Panel

// == Website's pages

// Home
$app->pages->add('home', '/home', 'icon-home');

// Services
$servicePagesptions = [
  'title' => 'services',
  'icon' => 'icon-support'
];

$app->pages->group($servicePagesptions, function($route) {
  $route->add('main', '/services');
});

// Contact
$app->pages->add('contact', '/contact', 'icon-envelope');

// Privacy
$app->pages->add('privacy', '/privacy', 'icon-shield');

// Imprint
$app->pages->add('imprint', '/imprint', 'icon-info');

// Not found
$app->pages->add('not found', '/404', 'fas fa-times');

// == Website's routes
if ($app->request->isRequestToAdminManagement()) return;


$app->route->add('/', 'Website/Home');
$app->route->add('/home', 'Website/Home');

// Services
$servicesRouteptions = [
  'prefix' => '/services',
  'controller' => 'Website',
  'middleware' => [],
];

$app->route->group($servicesRouteptions, function($route) {
  $route->add('/', 'Services');
});

// Contact
$app->route->add('/contact', 'Website/Contact');

// Privacy
$app->route->add('/privacy', 'Website/privacy');

// Imprint
$app->route->add('/imprint', 'Website/Imprint');
