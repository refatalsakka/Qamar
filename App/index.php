<?php

use System\Application;

$app = Application::getInstance();

//Default
$app->route->add('/', 'Users/Home', 'GET');

$app->route->add('/admin/home', 'Admin\Home');
$app->route->add('/admin/login', 'Admin\Login', 'GET');
$app->route->add('/admin/login/submit', 'Admin\Login@submit', 'POST');


// Share Admin Layout
$app->share('admin', function($app) {
    return $app->load->controller('Admin\Common\LayoutController');
});