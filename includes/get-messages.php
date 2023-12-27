<?php
if (count(get_included_files()) == 1) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}

/**
 * @param $user
 * @return Message[]
 * @throws Exception
 */
function getMessages($user): array
{
    if ($user->getIsAdmin()) {
        $messages = new Message();
    } else {
        $messages = Message::where("userID", $user->getID());
    }
    if (isset($_REQUEST["groupId"])) {
        $messages = $messages->where("groupId", $_REQUEST["groupId"]);
    }
    if (isset($_REQUEST["id"])) {
        $messages = $messages->where("ID", $_REQUEST["id"]);
    }
    if (isset($_REQUEST["status"])) {
        $messages = $messages->where("status", $_REQUEST["status"]);
    }
    if (isset($_REQUEST["deviceID"])) {
        $messages = $messages->where("deviceID", $_REQUEST["deviceID"]);
    }
    if (isset($_REQUEST["simSlot"])) {
        $messages = $messages->where("simSlot", $_REQUEST["simSlot"]);
    }
    if (isset($_REQUEST["startTimestamp"])) {
        $messages = $messages->where("sentDate", date("Y-m-d H:i:s", $_REQUEST["startTimestamp"]), ">=");
    }
    if (isset($_REQUEST["endTimestamp"])) {
        $messages = $messages->where("sentDate", date("Y-m-d H:i:s", $_REQUEST["endTimestamp"]), "<=");
    }
    return $messages->read_all();
}