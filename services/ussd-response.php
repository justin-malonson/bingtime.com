<?php

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../vendor/autoload.php";

date_default_timezone_set(TIMEZONE);
set_time_limit(20);

try {
    if (isset($_POST["androidId"]) && isset($_POST["userId"]) && isset($_POST["ussdId"]) && isset($_POST["response"])) {
        $device = new Device();
        $device->setAndroidID($_POST["androidId"]);
        $device->setUserID($_POST["userId"]);
        if ($device->read()) {
            $ussd = new Ussd();
            $ussd->setID($_POST["ussdId"]);
            $ussd->setUserID($_POST["userId"]);
            $ussd->setDeviceID($device->getID());
            if ($ussd->read(false)) {
                $ussd->setResponse($_POST["response"]);
                $ussd->setResponseDate(date("Y-m-d H:i:s"));
                $ussd->save();

                $device->getUser()->callWebhook('ussdRequest', $ussd);
                echo json_encode(["success" => true, "data" => null, "error" => null]);
            }
        } else {
            echo json_encode(["success" => false, "data" => null, "error" => ["code" => 401, "message" => __("error_device_not_found")]]);
        }
    } else {
        throw new Exception(__("error_invalid_request_format"));
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "data" => null, "error" => ["code" => 500, "message" => $e->getMessage()]]);
}
