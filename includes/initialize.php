<?php
if (count(get_included_files()) == 1) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}

// $_SERVER['PHP_SELF'] is dangerous if misused. If login.php/nearly_arbitrary_string is requested, $_SERVER['PHP_SELF'] will contain not just login.php, but the entire login.php/nearly_arbitrary_string.
// Use $_SERVER['SCRIPT_NAME'] instead of $_SERVER['PHP_SELF'].
$currentPage = basename($_SERVER['SCRIPT_NAME']);

array_walk_recursive($_REQUEST, 'trimByReference');
array_walk_recursive($_GET, 'trimByReference');
array_walk_recursive($_POST, 'trimByReference');

$accessibleScripts = [
    "index.php",
    "login-form.php",
    "reset-password.php",
    "reset-password-link.php",
    "register.php",
    "register-user.php"
];

if (in_array($currentPage, $accessibleScripts)) {
    require_once __DIR__ . "/set-language.php";
}
