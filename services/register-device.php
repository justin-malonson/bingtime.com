<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../vendor/autoload.php";

if (isset($_POST["language"])) {
    $files = getLanguageFiles();
    foreach ($files as $file) {
        if (strtolower($_POST["language"]) === strtolower($file)) {
            $language = $file;
            setLanguage($language);
            break;
        }
    }
}

if (isset($_POST["androidId"]) && isset($_POST["model"])) {
    try {
        require_once __DIR__ . "/../includes/get-user.php";
        if (isset($user)) {
            if ($user) {
                if ($user->isActiveDevicesLimitReached()) {
                    $errorCode = 401;
                    $error = __("error_devices_limit_reached");
                } else {
                    MysqliDb::getInstance()->startTransaction();
                    $device = new Device();
                    $device->setAndroidID($_POST["androidId"]);
                    $device->setUserID($user->getID());
                    $device->read();
                    $device->setModel($_POST["model"]);
                    if (isset($_POST["androidVersion"])) {
                        $device->setAndroidVersion($_POST["androidVersion"]);
                    }
                    if (isset($_POST["appVersion"])) {
                        $device->setAppVersion($_POST["appVersion"]);
                    }
                    $device->setEnabled(0);
                    $device->save();
                    if (isset($language)) {
                        $user->setLanguage($language);
                        $user->save();
                    }
                    $deviceUser = new DeviceUser();
                    $deviceUser->setDeviceID($device->getID());
                    $deviceUser->setUserID($user->getID());
                    $deviceUser->save(true, ['active' => 1]);
                    if (isset($_POST["sims"])) {
                        $device->saveSims(json_decode($_POST["sims"]));
                    }
                    MysqliDb::getInstance()->commit();
                    if (defined("SENDER_ID")) {
                        $response = [
                            "success" => true,
                            "data" => ["senderId" => SENDER_ID, "purchaseCode" => PURCHASE_CODE, "device" => $device],
                            "error" => null
                        ];
                    } else {
                        $response = [
                            "success" => true,
                            "data" => ["purchaseCode" => PURCHASE_CODE, "device" => $device],
                            "error" => null
                        ];
                    }
                    echo json_encode($response);
                    die;
                }
            } else {
                $errorCode = 401;
                if (isset($_POST["key"])) {
                    $error = __("error_parsing_qr_code");
                } else {
                    $error = __("error_incorrect_credentials");
                }
            }
        } else {
            die;
        }
    } catch (Throwable $t) {
        $errorCode = 500;
        $error = $t->getMessage();
    }
    $response = ["success" => false, "data" => null, "error" => ["code" => $errorCode, "message" => $error]];
    echo json_encode($response);
}