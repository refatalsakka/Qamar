<?php

$app = app();

$app->route->get('/', 'Website/Home');
$app->route->get('/home', 'Website/Home');
$app->route->get('/language/:text', 'Website/Language');
