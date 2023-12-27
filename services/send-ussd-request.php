<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../vendor/autoload.php";

date_default_timezone_set(TIMEZONE);

try {
    require_once __DIR__ . "/../includes/get-user.php";

    if (!empty($_REQUEST["request"]) && !empty($_REQUEST["device"]) && isset($user)) {
        if ($user) {
            try {
                $simSlot = null;
                if (isset($_REQUEST["sim"]) && ctype_digit($_REQUEST["sim"])) {
                    $simSlot = $_REQUEST["sim"];
                }

                $ussdRequest = DeviceUser::initiateUssdRequest($_REQUEST["request"], $user->getID(), $_REQUEST["device"], $simSlot);
                echo json_encode(["success" => true, "data" => ["request" => $ussdRequest], "error" => null]);
            } catch (InvalidArgumentException $e) {
                echo json_encode(["success" => false, "data" => null, "error" => ["code" => 404, "message" => $e->getMessage()]]);
            }
        } else {
            echo json_encode(["success" => false, "data" => null, "error" => ["code" => 401, "message" => isset($_REQUEST["key"]) ? __("error_incorrect_api_key") : __("error_incorrect_credentials")]]);
        }
    } else {
        echo json_encode(["success" => false, "data" => null, "error" => ["code" => 400, "message" => __("error_invalid_request_format")]]);
    }
} catch (Throwable $t) {
    echo json_encode(["success" => false, "data" => null, "error" => ["code" => 500, "message" => $t->getMessage()]]);
}