<?php
try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (!isset($_REQUEST["lastId"]) || !ctype_digit($_REQUEST["lastId"])) {
        throw new Exception("Invalid lastId");
    }

    $lastId = $_REQUEST["lastId"];
    $messages = Message::where("status", "Received")
        ->where("userID", $_SESSION["userID"])
        ->where("ID", $lastId, ">")
        ->read_all();

    $numbers = [];
    foreach ($messages as $message) {
        if (in_array($message->getNumber(), $numbers)) {
            continue;
        }
        $numbers[] = $message->getNumber();
    }

    /** @var User $logged_in_user */
    $contacts = $logged_in_user->getContacts($numbers);
    foreach ($messages as $message) {
        if ($message->getID() > $lastId) {
            $lastId =  $message->getID();
        }
    }

    echo json_encode([
        "result" => [
            "lastId" => $lastId,
            "messages" => array_map(fn($message) => [
                "number" => isset($contacts[$message->getNumber()]) ? $contacts[$message->getNumber()] . " ({$message->getNumber()})" : $message->getNumber(),
                "message" => $message->getMessage()
            ], $messages)
        ]
    ]);
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}