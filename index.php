<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use System\Application;
use System\File;

$app = Application::getInstance(new File(__DIR__));

$app->run();

