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
$app->route->add('/services/webdesign', 'Users/Services@webdesign');
$app->route->add('/services/seo', 'Users/Services@seo');
$app->route->add('/services/browser-extension', 'Users/Services@browserExtension');
$app->route->add('/services/media-box', 'Users/Services@mediaBox');
$app->route->add('/services/server', 'Users/Services@server');
$app->route->add('/services/datenbank', 'Users/Services@database');
$app->route->add('/services/display-webung', 'Users/Services@displayAdvertising');
$app->route->add('/services/imagefilm', 'Users/Services@imagefilm');
$app->route->add('/services/native-werbung', 'Users/Services@nativeAdvertising');
$app->route->add('/services/mobile-werbung', 'Users/Services@mobileAdvertising');

//Team
$app->route->add('/team', 'Users/Team');

//Prise
$app->route->add('/preis', 'Users/Prise');

//Contact
$app->route->add('/kontakt', 'Users/Contact');

//Data Protection
$app->route->add('/datenschutz', 'Users/dataProtection');

//imprint
$app->route->add('/immpresssum', 'Users/Imprint');


// ====== Admins ====== //

// Share Admin Layout
$app->share('adminLayout', function($app) {
    return $app->load->controller('Admin\Common\LayoutController');
});

$app->route->add('/admin', 'Admin/Home');

//Login
$app->route->add('/login', 'Admin/Login', 'GET', 'auth');
$app->route->add('/login/submit', 'Admin/Login@submit', 'POST');

if (strpos($app->request->url(), '/admin') === 0) $app->load->middleware('auth')->handle();
if (strpos($app->request->url(), '/admin') === 0) $app->load->middleware('permissions')->handle();

$app->route->add('/admin/home', 'Admin\Home');
$app->route->add('/admin/posts', 'Admin\Posts@posts');