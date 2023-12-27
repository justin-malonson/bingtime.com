<?php
if (count(get_included_files()) == 1) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}

if (isset($_REQUEST["email"]) && isset($_REQUEST["password"])) {
    $user = User::login($_REQUEST["email"], $_REQUEST["password"]);
} else if (isset($_REQUEST["key"])) {
    $user = new User();
    $user->setApiKey($_REQUEST["key"]);
    $user = $user->read();
}