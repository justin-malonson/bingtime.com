<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../vendor/autoload.php";

date_default_timezone_set(TIMEZONE);

if (isset($_POST["groupId"])) {
    try {
        MysqliDb::getInstance()->startTransaction();
        $now = date("Y-m-d H:i:s");
        if (empty($_POST["limit"])) {
            $messages = Message::where("groupId", $_POST["groupId"])->where("status", "Pending")->read_all(false);
            Message::where("groupId", $_POST["groupId"])->where("status", "Pending")->update_all(["status" => "Queued", "deliveredDate" => $now]);
        } else {
            Message::setPageLimit($_POST["limit"]);
            $messages = Message::where("groupId", $_POST["groupId"])->where("status", "Pending")->read_all(false, 1);
            $totalCount = Message::getTotalCount();
            $ids = [];
            foreach ($messages as $message) {
                $ids[] = $message->getID();
            }
            if ($ids) {
                Message::where("ID", $ids, "IN")->update_all(["status" => "Queued", "deliveredDate" => $now]);
            }
        }
        MysqliDb::getInstance()->commit();
        foreach ($messages as $message) {
            $message->setStatus("Queued");
            $message->setDeliveredDate($now);
        }
        $response =
            [
                "success" => true,
                "data" => [
                    "messages" => $messages,
                    "totalCount" => $totalCount ?? count($messages)
                ],
                "error" => null
            ];
        echo json_encode($response);
    } catch (Throwable $t) {
        $response = ["success" => false, "data" => null, "error" => ["code" => 500, "message" => $t->getMessage()]];
    }
}