<?php

use System\Application;

$app = Application::getInstance();

// ====== Users ====== //

// Share Users Layout
$app->share('usersLayout', function($app) {
    return $app->load->controller('Users\Common\LayoutController');
});

//Home
$app->route->add('/', 'Users/Home');
$app->route->add('/home', 'Users/Home');

//Services
$app->route->add('/services', 'Users/Services');

//Team
$app->route->add('/team', 'Users/Team');

//Contact
$app->route->add('/contact', 'Users/Contact');

//Data Protection
$app->route->add('/data-protection', 'Users/dataProtection');

//Imprint
$app->route->add('/imprint', 'Users/Imprint');


// ====== Admins ====== //

// Share Admin Layout
$app->share('adminLayout', function($app) {
    return $app->load->controller('Admin\Common\LayoutController');
});

$app->route->add('/admin', 'Admin/Home');

//Login
$app->route->add('/admin/login', 'Admin/Login');
$app->route->add('/admin/submit', 'Admin/Login@submit', 'POST');
$app->route->add('/admin/logout', 'Admin/Logout');

//Profile
$app->route->add('/admin/profile', 'Admin/Profile');

//Settings
$app->route->add('/admin/settings', 'Admin/Settings');


if (strpos($app->request->url(), '/admin') === 0) $app->load->middleware('auth')->handle();
if (strpos($app->request->url(), '/admin') === 0) $app->load->middleware('permissions')->handle();

$app->route->add('/admin/home', 'Admin\Home');
$app->route->add('/admin/posts', 'Admin\Posts@posts');