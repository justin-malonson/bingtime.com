<?php
if (count(get_included_files()) == 1) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}

header('Cache-Control: no cache');
require_once __DIR__ . "/session.php";

/*
 * @link https://stackoverflow.com/a/1270960/1273550
 */
if (isset($_SESSION["userID"])) {
    $_SESSION['LAST_ACTIVITY'] = time();
    if (isset($_COOKIE["DEVICE_ID"])) {
        $currentDevice = Device::getById($_COOKIE["DEVICE_ID"], $_SESSION["userID"]);
        if ($currentDevice && $currentDevice->getEnabled()) {
            $logged_in_user = $currentDevice->getUser();
        }
    } else {
        $logged_in_user = User::getById($_SESSION["userID"]);
    }

    if (empty($logged_in_user)) {
        require_once __DIR__ . "/../logout.php";
        exit();
    } else {
        $_SESSION['timeZone'] = $logged_in_user->getTimeZone();
        $_SESSION['name'] = $logged_in_user->getName();
        require_once __DIR__ . "/set-language.php";
    }
} else {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode(array(
            'redirect' => "index.php"
        ));
        exit();
    }
    header("location:index.php");
    exit();
}