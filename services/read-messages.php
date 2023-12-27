<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../vendor/autoload.php";

date_default_timezone_set(TIMEZONE);

try {
    require_once __DIR__ . "/../includes/get-user.php";
    if (isset($user)) {
        if ($user) {
            require_once __DIR__ . "/../includes/get-messages.php";
            $messages = getMessages($user);

            if (count($messages) > 0) {
                echo json_encode(["success" => true, "data" => ["messages" => $messages], "error" => null]);
            } else {
                echo json_encode(["success" => false, "data" => null, "error" => ["code" => 404, "message" => __("no_messages_found")]]);
            }
        } else {
            echo json_encode(["success" => false, "data" => null, "error" => ["code" => 401, "message" => isset($_REQUEST["key"]) ? __("error_incorrect_api_key") : __("error_incorrect_credentials")]]);
        }
        die;
    }
    echo json_encode(["success" => false, "data" => null, "error" => ["code" => 400, "message" => __("error_invalid_request_format")]]);
} catch (Throwable $t) {
    echo json_encode(["success" => false, "data" => null, "error" => ["code" => 500, "message" => $t->getMessage()]]);
}
