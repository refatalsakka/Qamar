<?php

$app = app();

// ====== Website ====== //

// Share Website Layout
$app->share('websiteLayout', function($app) {
    return $app->load->controller('Website\Common\LayoutController');
});

$app->route->add('/', 'Website/Home');
$app->route->add('/home', 'Website/Home');

//Services
$app->route->add('/services', 'Website/Services');

//Team
$app->route->add('/team', 'Website/Team');

//Contact
$app->route->add('/contact', 'Website/Contact');

//Data Protection
$app->route->add('/data-protection', 'Website/dataProtection');

//Imprint
$app->route->add('/imprint', 'Website/Imprint');

// ====== Admins ====== //

// Share Admin Layout
$app->share('adminLayout', function($app) {
    return $app->load->controller('Admin\Common\LayoutController');
});


$adminOptions = [
    'prefix' => '/admin',
    'controller' => 'Admin',
    'middleware' => ['admin', ['ajax']]
];

$app->route->group($adminOptions, function ($route) {

    $route->add('/', 'Home');
    
    //Login
    $route->add('/login', 'Login');
    $route->add('/submit', 'Login@submit', 'POST');
    $route->add('/logout', 'Logout');

    //Profile
    $route->add('/profile', 'Profile', 'GET', ['auth']);

    //Settings
    $route->add('/settings', 'Settings');
});