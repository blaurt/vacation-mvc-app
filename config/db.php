<?php

/**
 * Database info
 */

$host = '127.0.0.1';
$db = 'humanity_test_prj';
$user = 'homestead';
$pass = 'secret';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

return [
    'dsn' => $dsn,
    'user' => $user,
    'pass' => $pass,
];