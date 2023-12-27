<?php
/**
 * @var User $logged_in_user
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (!empty($_POST["responseID"]) && isset($_POST["message"]) && trim($_POST["message"]) !== "" && isset($_POST["response"]) && trim($_POST["response"]) !== "") {
        $response = new Response();
        $response->setID($_POST["responseID"]);
        $response->setUserID($_SESSION["userID"]);
        if ($response->read()) {
            $response->setMessage(trim($_POST["message"]));
            $response->setResponse($_POST["response"]);
            $response->setMatchType($_POST["matchType"]);
            $response->setEnabled($_POST["enabled"]);
            $response->save();
        }

        echo json_encode([
            "result" => __("success_update_response")
        ]);
    } else {
        throw new Exception(__("error_missing_fields"));
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}
