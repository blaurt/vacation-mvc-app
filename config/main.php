<?php
/**
 * Application config
 */

define('APP_DIR', __DIR__ . '/../');
defined('DEBUG_MODE') or define('DEBUG_MODE', true);
if (DEBUG_MODE == 1) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

return [
    'app_name' => '"Humanity" Test project',
    'db' => require_once 'db.php',
    'salt' => 'salt_string',
    'defaultRoute' => 'default/index',
    'error_message' => 'Something goes wrong. If it will appear again, we will fix it ASAP.',
];