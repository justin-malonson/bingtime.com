<?php
/**
 * @var User $logged_in_user
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (!empty($_POST["webHookURL"])) {
        if (filter_var($_POST["webHookURL"], FILTER_VALIDATE_URL)) {
            $logged_in_user->setWebHook($_POST["webHookURL"]);
            $logged_in_user->save();
            echo json_encode([
                "result" => __("success_save_webhook")
            ]);
        } else {
            throw new Exception(__("error_invalid_webhook_url"));
        }
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}
