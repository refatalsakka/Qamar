<?php

require __DIR__ . '/vendor/autoload.php';

use System\Application;
use System\File;

$app = Application::getInstance(new File(__DIR__));

$app->run();