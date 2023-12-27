<?php

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (isset($_POST["name"]) && isset($_POST["deviceID"])) {
        $name = $_POST["name"];
        if (function_exists('mb_strlen')) {
            $length = mb_strlen($name, 'UTF-8');
        } else {
            $length = strlen($name);
        }
        if ($length > 25) {
            throw new Exception(__("error_device_name"));
        } else {
            $deviceUser = DeviceUser::getById($_POST["deviceID"], $_SESSION["userID"]);
            $deviceUser->setName($name);
            $deviceUser->save();
        }
        echo json_encode([
            'result' => [
                "message" => __("success_device_settings"),
                "data" => [
                    "name" => strval($deviceUser)
                ]
            ]
        ]);
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}
