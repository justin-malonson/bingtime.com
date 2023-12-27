<?php
/**
 * @var User $logged_in_user
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    $logged_in_user->setWebHook(null);
    $logged_in_user->save(false);
    echo json_encode([
        "result" => __("success_remove_webhook")
    ]);
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}
