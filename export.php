<?php

try {
    require_once __DIR__ . "/includes/login.php";

    if (isset($_REQUEST["startDate"]) && isset($_REQUEST["endDate"])) {
        $start_date = $_REQUEST["startDate"];
        $end_date = $_REQUEST["endDate"];
        require_once __DIR__ . "/includes/search.php";
        if (count($messages) > 0) {
            objectsToExcel($messages, "Messages_{$start_date}_{$end_date}.csv", ["number" => __("mobile_number"), "message" => __("message"), "status" => __("status"), "sentDate" => __("sent_date"), "deliveredDate" => __("delivered_date")], array("userID", "deviceID", "ID", "groupID", "resultCode", "errorCode", "retries", "expiryDate"));
        } else {
            header("location:messages.php?" . $_SERVER['QUERY_STRING']);
        }
    }
} catch (Exception $e) {
    echo json_encode(array(
        "error" => $e->getMessage()
    ));
}

