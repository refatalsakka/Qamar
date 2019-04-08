<?php

use System\Application;

$app = Application::getInstance();

// $app->route->add('/', 'Home');

$app->route->add('/posts/:text/:id', 'Posts');
