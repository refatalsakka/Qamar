<?php

$config = require(__DIR__ . DS . '..' . DS . 'config.php');

if ($config['mode'] == 'production' || $config['mode'] == 'pro') {
  error_reporting(0);
  ini_set("display_errors", 0);
  return false;
} else {
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
  return true;
}
