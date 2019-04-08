<?php

require __DIR__ . '/Vendor/System/Application.php';
require __DIR__ . '/Vendor/System/File.php';

use System\Application;
use System\File;

$app = Application::getInstance(new File(__DIR__));

$app->run();