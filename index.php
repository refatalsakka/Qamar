<?php

const DS = DIRECTORY_SEPARATOR;

require_once __DIR__ . DS . 'vendor' . DS . 'autoload.php';

use System\Application;
use System\File;

$app = Application::getInstance(new File(__DIR__));

$app->run();
