<?php

$app = app();

// Home
$app->route->get('/', 'Website/Home');
$app->route->get('/home', 'Website/Home');
