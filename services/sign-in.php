<?php
require_once __DIR__ . "/../includes/session.php";

if (isset($_POST["androidId"]) && isset($_POST["userId"])) {
    try {
        $device = new Device();
        $device->setAndroidID($_POST["androidId"]);
        $device->setUserID($_POST["userId"]);
        $device->setEnabled(1);
        if ($device->read()) {
            if (isset($_POST["sims"])) {
                $device->saveSims(json_decode($_POST["sims"]));
            }
            $device->getUser()->setLastLogin(date('Y-m-d H:i:s'));
            $device->getUser()->setLastLoginIP(getUserIpAddress());
            $device->getUser()->save();
            if (isset($_POST["androidVersion"]) && isset($_POST["appVersion"])) {
                $device->setAndroidVersion($_POST["androidVersion"]);
                $device->setAppVersion($_POST["appVersion"]);
                $device->save();
            }
            $_SESSION["userID"] = $device->getUserID();
            $_SESSION["email"] = $device->getUser()->getEmail();
            $_SESSION["name"] = $device->getUser()->getName();
            $_SESSION["isAdmin"] = $device->getUser()->getIsAdmin();
            $_SESSION["timeZone"] = $device->getUser()->getTimeZone();
            session_commit();
            $response =
                [
                    "success" => true,
                    "data" => [
                        "sessionId" => get_cookie(APP_SESSION_NAME),
                        "device" => $device,
                    ],
                    "error" => null
                ];
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