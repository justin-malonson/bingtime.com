<?php
/**
 * @var User $logged_in_user
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (isset($_POST["message"]) && trim($_POST["message"]) !== "" && !empty($_POST["name"])) {
        $template = new Template();
        $template->setName($_POST["name"]);
        $template->setMessage($_POST["message"]);
        $template->setUserID($_SESSION["userID"]);
        $template->save();

        echo json_encode([
            "result" => __("success_add_template")
        ]);
    } else {
        throw new Exception(__("error_missing_fields"));
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}
