<?php
/**
 * @var string $start_date
 * @var string $end_date
 */

if (count(get_included_files()) == 1) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}

if (empty($_REQUEST["status"]) || $_REQUEST["status"] != "Scheduled") {
    $message = Message::where("Message.sentDate", getDatabaseTime($start_date . " 00:00:00")->format('Y-m-d H:i:s'), ">=")
        ->where("Message.sentDate", getDatabaseTime($end_date . " 23:59:59")->format('Y-m-d H:i:s'), "<=");
} else {
    $message = Message::where("Message.schedule", getDisplayTime($start_date . " 00:00:00")->getTimestamp(), ">=")
        ->where("Message.schedule", getDisplayTime($end_date . " 23:59:59")->getTimestamp(), "<=");
}

$message->orderBy("Message.sentDate", "DESC")
    ->orderBy("Message.userID", "ASC")
    ->orderBy("Message.deviceID", "ASC");
if ($_SESSION["isAdmin"]) {
    if (isset($_REQUEST["user"]) && ctype_digit($_REQUEST["user"]))
        $message->where("Message.userID", $_REQUEST["user"]);
} else {
    $message->where("Message.userID", $_SESSION["userID"]);
}
if (isset($_REQUEST["device"])) {
    if (empty($_REQUEST["device"])) {
        $message->where("Message.deviceID", null, "IS");
    } else if (ctype_digit($_REQUEST["device"])) {
        $message->where("Message.deviceID", $_REQUEST["device"]);
    }
}
if (!empty($_REQUEST["status"]))
    $message->where("Message.status", $_REQUEST["status"]);
if (!empty($_REQUEST["type"]))
    $message->where("Message.type", $_REQUEST["type"]);
if (!empty($_REQUEST["mobileNumber"]))
    $message->where("Message.number", "%{$_REQUEST['mobileNumber']}%", "LIKE");
if (isset($_REQUEST["message"])) {
    $messageText = trim($_REQUEST["message"]);
    if ($messageText) {
        $message->where("Message.message", "%{$messageText}%", "LIKE");
    }
}

$currentPage = basename($_SERVER['PHP_SELF']);
$pageNo = 1;
if (!empty($_REQUEST["pageLimit"]) && ctype_digit($_REQUEST["pageLimit"])) {
    Message::setPageLimit($_REQUEST["pageLimit"]);
}
if (!empty($_REQUEST["page"]) && ctype_digit($_REQUEST["page"])) {
    $pageNo = $_REQUEST["page"];
}
/** @var Message[] $messages */
$messages = $message->read_all(false, $pageNo);