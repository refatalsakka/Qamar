<?php

$config = require($_SERVER['DOCUMENT_ROOT'] . DS . 'config.php');

if ($config['env'] == 'production' || $config['env'] == 'pro') {

  error_reporting(0);

  ini_set("display_errors", 0);

} else {

  error_reporting(E_ALL);

  ini_set("display_errors", 1);
}
