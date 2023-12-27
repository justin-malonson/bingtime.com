<?php
/**
 * @var User $logged_in_user
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    $logged_in_user->setApiKey(generateAPIKey());
    $logged_in_user->save();
    echo json_encode(array(
        'result' => __("success_regenerate_api_key")
    ));
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}