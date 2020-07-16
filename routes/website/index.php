<?php

$app = app();

if ($app->request->isRequestToAdminManagement()) {
  return;
}

// Home
$app->route->add('/', 'Website/Home');
$app->route->add('/home', 'Website/Home');


