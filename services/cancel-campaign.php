<?php

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../vendor/autoload.php";

date_default_timezone_set(TIMEZONE);

try {
    if (isset($_POST["groupId"])) {
        Message::where('groupID', $_POST["groupId"])->where('status', ['Queued', 'Pending'], 'IN')->update_all(['status' => 'Canceled', 'deliveredDate' => date('Y-m-d H:i:s')]);
        echo json_encode(["success" => true, "data" => null, "error" => null]);
    } else {
        echo json_encode(["success" => false, "data" => null, "error" => ["code" => 400, "message" => __("error_invalid_request_format")]]);
    }
} catch (Throwable $t) {
    echo json_encode(["success" => false, "data" => null, "error" => ["code" => 500, "message" => $t->getMessage()]]);
}