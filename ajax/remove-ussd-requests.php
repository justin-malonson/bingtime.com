<?php

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (isset($_POST["requests"]) && is_array($_POST["requests"])) {
        $count = 0;
        MysqliDb::getInstance()->startTransaction();
        foreach ($_POST["requests"] as $requestID) {
            $ussd = new Ussd();
            $ussd->setID($requestID);
            if (!$_SESSION["isAdmin"]) {
                $ussd->setUserID($_SESSION["userID"]);
            }
            if ($ussd->read()) {
                $ussd->delete();
                $count++;
            }
        }
        MysqliDb::getInstance()->commit();
        $success = $count > 1 ? __("success_ussd_requests_removed", ["count" => $count]) : __("success_ussd_request_removed", ["count" => $count]);
        echo json_encode(array(
            'result' => $success
        ));
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}