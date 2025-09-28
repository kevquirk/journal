<?php
$config = require __DIR__ . '/config.php';
$config['date_only'] = $config['date_only'] ?? False;

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/Parsedown.php';



// Prevent session hijacking
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);

session_start();

// Only enforce login if we're not on login.php
if (!str_contains($_SERVER['SCRIPT_NAME'], 'login.php')) {
    require_once __DIR__ . '/auth.php';
}

if (!isset($page_title)) {
    $page_title = $config['page_title'];
}

if (!isset($page_description)) {
    $page_description = $config['page_description'];
}

date_default_timezone_set($config['timezone']);

$Parsedown = new Parsedown();