<?php

$app = app();

// Home
$app->route->add('/', 'Website/Home', 'GET', 'Authenticate');
$app->route->add('/home', 'Website/Home');
