<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../vendor/autoload.php";

date_default_timezone_set(TIMEZONE);

try {
    require_once __DIR__ . "/../includes/get-user.php";
    if (isset($user)) {
        if ($user) {
            $deviceUsers = $user->getDevices();
            $sims = $user->getSims();
            $devices = [];
            foreach ($deviceUsers as $deviceUser) {
                $devices[] = [
                    "id" => $deviceUser->getDeviceID(),
                    "name" => $deviceUser->getName(),
                    "model" => $deviceUser->getDevice()->getModel(),
                    "androidVersion" => $deviceUser->getDevice()->getAndroidVersion(),
                    "appVersion" => $deviceUser->getDevice()->getAppVersion(),
                    "sims" => array_key_exists($deviceUser->getDeviceID(), $sims) ? $sims[$deviceUser->getDeviceID()] : []
                ];
            }
            echo json_encode([
                "success" => true,
                "data" => ["devices" => $devices],
                "error" => null
            ]);
        } else {
            echo json_encode(["success" => false, "data" => null, "error" => ["code" => 401, "message" => isset($_REQUEST["key"]) ? __("error_incorrect_api_key") : __("error_incorrect_credentials")]]);
        }
        die;
    }
    echo json_encode(["success" => false, "data" => null, "error" => ["code" => 400, "message" => __("error_invalid_request_format")]]);
} catch (Throwable $t) {
    echo json_encode(["success" => false, "data" => null, "error" => ["code" => 500, "message" => $t->getMessage()]]);
}
