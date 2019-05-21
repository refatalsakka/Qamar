<?php

use System\Application;

$app = Application::getInstance();

//Users
$app->route->add('/', 'Users/Home', 'GET');
$app->route->add('/login', 'Users/Login', 'GET');
$app->route->add('/login/submit', 'Users/Login@submit', 'POST');
$app->route->add('/my-categories', 'Users/Categories', 'GET', ['auth']);

//Check if Admin Login
if (strpos($app->request->url(), '/admin') === 0) $app->load->middleware('auth')->index();

//Admins
$app->route->add('/admin/home', 'Admin\Home');
$app->route->add('/admin/posts', 'Admin\Posts@posts');
$app->route->add('/admin/post/:id', 'Admin\Posts@post');

// Share Admin Layout
$app->share('admin', function($app) {
    return $app->load->controller('Admin\Common\LayoutController');
});