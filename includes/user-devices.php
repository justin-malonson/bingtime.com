<?php
/**
 * @var User $logged_in_user
 */

if (count(get_included_files()) == 1) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}

$data = [];
if ($_SESSION["isAdmin"]) {
    $users = array();
    foreach (User::read_all() as $user) {
        $data[$user->getID()][null] = __("unknown_device");
        $users[$user->getID()] = $user;
    }
    $deviceUsers = DeviceUser::read_all();
} else {
    $data[$_SESSION["userID"]][null] = __("unknown_device");
    $deviceUsers = $logged_in_user->getDevices(false);
}

foreach ($deviceUsers as $deviceUser) {
    $data[$deviceUser->getUserID()][$deviceUser->getDeviceID()] = htmlentities(strval($deviceUser), ENT_QUOTES);
}