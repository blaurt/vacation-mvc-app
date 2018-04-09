<?php
declare(strict_types=1);

define('DEBUG_MODE', 1);

require_once __DIR__ . '/../vendor/autoload.php';
$config = require(__DIR__ . '/../config/main.php');
$app = app\core\AppCore::getInstance($config);


$app->run();
