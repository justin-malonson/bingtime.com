<?php
/**
 * @var User $logged_in_user
 */

try {
    require_once __DIR__ . "/../includes/login.php";

    if (empty($_POST["mobileNumber"]) || empty($_POST["devices"]) || ($_POST["type"] === 'sms' && empty($_POST["message"]))) {
        throw new Exception(__("error_missing_fields"));
    } else {
        $messages = [];
        $attachments = $logged_in_user->upload("attachments");
        if (count($attachments) > 0) {
            $attachments = implode(',', $attachments);
        } else {
            if ($_POST["type"] === 'mms' && empty($_POST["message"])) {
                throw new Exception(__("error_missing_fields"));
            }
            $attachments = null;
        }
        $mobileNumbers = explode(",", $_POST["mobileNumber"]);
        foreach ($mobileNumbers as $mobileNumber) {
            $messages[] = ['number' => $mobileNumber, 'message' => $_POST["message"], 'attachments' => $attachments, 'type' => $_POST["type"]];
        }
        $schedule = null;
        if (isset($_POST["schedule"])) {
            $schedule = new DateTime($_POST["schedule"], new DateTimeZone($_SESSION["timeZone"]));
            $schedule = $schedule->getTimestamp();
        }
        Message::sendMessages($messages, $logged_in_user, is_array($_POST["devices"]) ? $_POST["devices"] : [$_POST["devices"]], $schedule, $_POST["prioritize"]);
        $success = is_null($schedule) ? __("success_sent") : __("success_scheduled");
        echo json_encode([
            "result" => $success
        ]);
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => nl2br(htmlentities($t->getMessage(), ENT_QUOTES))
    ));
}