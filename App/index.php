<?php

use System\Application;

$app = Application::getInstance();

$app->route->add('/', 'Home');


// Share Admin Layout
$app->share('adminLayout', function($app) {
    return $app->loader->controller('Admin\Common\LayoutController');
});

// Admin Routes
$app->route->add('/admin/login', 'Admin\Login');
$app->route->add('/admin/category', 'Admin\CategoryController');