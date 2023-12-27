<?php
if (count(get_included_files()) == 1) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}

$timeout = 86400;
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../vendor/autoload.php";
date_default_timezone_set(TIMEZONE);
session_cache_limiter('nocache');
session_set_cookie_params([
    'lifetime' => $timeout,
    'path' => '/',
    'samesite' => 'Lax'
]);
$JWTSession = new ravibpatel\JWTSession\JWTSession($timeout, APP_SECRET_KEY, false, APP_SESSION_NAME);
$JWTSession->setSessionHandler(true);

require_once __DIR__ . "/initialize.php";
