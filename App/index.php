<?php

use System\Application;

$app = Application::getInstance();

//Default
$app->route->add('/', 'Users/Home', 'GET', ['auth']);
$app->route->add('/home', 'Users/Home');

$app->route->add('/admin/home', 'Admin\Home');
$app->route->add('/admin/login', 'Admin\Login');
$app->route->add('/admin/login/submit', 'Admin\Login@submit', 'POST');


// Share Admin Layout
$app->share('admin', function($app) {
    return $app->loader->controller('Admin\Common\LayoutController');
});