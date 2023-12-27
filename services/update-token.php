<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../vendor/autoload.php";

if (isset($_POST["androidId"]) && isset($_POST["userId"])) {
    try {
        $device = new Device();
        $device->setAndroidID($_POST["androidId"]);
        $device->setUserID($_POST["userId"]);
        if ($device->read()) {
            $device->setToken($_POST["token"] ?? null);
            $device->setEnabled(1);
            $device->save(false);
            $response = ["success" => true, "data" => ["device" => $device], "error" => null];
            echo json_encode($response);
            die;
        } else {
            $errorCode = 401;
            $error = __("error_device_not_found");
        }
    } catch (Throwable $t) {
        $errorCode = 500;
        $error = $t->getMessage();
    }
    $response = ["success" => false, "data" => null, "error" => ["code" => $errorCode, "message" => $error]];
    echo json_encode($response);
}