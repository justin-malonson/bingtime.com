<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../vendor/autoload.php";

if (isset($_POST["androidId"]) && isset($_POST["userId"])) {
    try {
        $device = new Device();
        $device->setAndroidID($_POST["androidId"]);
        $device->setUserID($_POST["userId"]);
        $device->setEnabled(1);
        if ($device->read()) {
            $device->setEnabled(0);
            $device->save();
        }
        echo json_encode(["success" => true, "data" => null, "error" => null]);
    } catch (Throwable $t) {
        echo json_encode(["success" => false, "data" => null, "error" => ["code" => 500, "message" => $t->getMessage()]]);
    }
}