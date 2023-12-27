<?php
/**
 * @var User $logged_in_user
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (!empty($_POST["currentPassword"]) && !empty($_POST["newPassword"])) {
        $userLogin = User::login($_SESSION["email"], $_POST["currentPassword"]);
        if ($userLogin) {
            $userLogin->setPassword($_POST["newPassword"]);
            $userLogin->save();
            echo json_encode([
                'result' => __("success_password_changed")
            ]);
        } else {
            throw new Exception(__("error_password_incorrect"));
        }
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}
