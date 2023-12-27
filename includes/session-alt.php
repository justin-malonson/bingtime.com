<?php
if (count(get_included_files()) == 1) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}
$timeout = 86400;
ini_set('session.gc_maxlifetime', $timeout);
ini_set('session.cookie_lifetime', $timeout);
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../vendor/autoload.php";
session_cache_limiter('nocache');
session_set_cookie_params([
    'lifetime' => $timeout,
    'path' => '/',
    'samesite' => 'Lax'
]);
session_name(APP_SESSION_NAME);
session_start();

require_once __DIR__ . "/initialize.php";