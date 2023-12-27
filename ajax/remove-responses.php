<?php

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (isset($_POST["responses"]) && is_array($_POST["responses"])) {
        $count = 0;
        MysqliDb::getInstance()->startTransaction();
        foreach ($_POST["responses"] as $responseID) {
            $response = new Response();
            $response->setID($responseID);
            $response->setUserID($_SESSION["userID"]);
            if ($response->read()) {
                $response->delete();
                $count++;
            }
        }
        MysqliDb::getInstance()->commit();
        $success = $count > 1 ? __("success_responses_removed", ["count" => $count]) : __("success_response_removed", ["count" => $count]);
        echo json_encode(array(
            'result' => $success
        ));
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}