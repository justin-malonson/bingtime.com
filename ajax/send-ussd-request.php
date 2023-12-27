<?php

try {
    require_once __DIR__ . "/../includes/login.php";

    if (empty($_POST["request"]) || empty($_POST["device"])) {
        throw new Exception(__("error_missing_fields"));
    } else {
        $simSlot = null;
        if (isset($_POST["sim"]) && ctype_digit($_POST["sim"])) {
            $simSlot = $_POST["sim"];
        }

        DeviceUser::initiateUssdRequest($_POST["request"], $_SESSION["userID"], $_POST["device"], $simSlot);
        echo json_encode([
            "result" => __("success_sent_ussd_request")
        ]);
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => htmlentities($t->getMessage(), ENT_QUOTES)
    ));
}
