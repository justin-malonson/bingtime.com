<?php
/**
 * @var User $logged_in_user
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (empty($_POST["contactsList"]) || empty($_POST["devices"]) || ($_POST["type"] === 'sms' && empty($_POST["message"]))) {
        throw new Exception(__("error_missing_fields"));
    } else {
        if (ContactsList::getContactsList($_POST["contactsList"], $_SESSION["userID"])) {
            $contacts = Contact::where("contactsListID", $_POST["contactsList"])
                ->where("subscribed", true)
                ->read_all(false);
            if (empty($contacts)) {
                throw new Exception(__("error_no_subscribers"));
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
                foreach ($contacts as $contact) {
                    $number = $contact->getNumber();
                    $message = $contact->getMessage($_POST["message"]);
                    $messages[] = ["number" => $number, "message" => $message, "attachments" => $attachments, 'type' => $_POST["type"]];
                }
                $schedule = null;
                if (isset($_POST["schedule"])) {
                    $schedule = new DateTime($_POST["schedule"], new DateTimeZone($_SESSION["timeZone"]));
                    $schedule = $schedule->getTimestamp();
                }
                Message::sendMessages($messages, $logged_in_user, $_POST["devices"], $schedule, $_POST["prioritize"]);
                $success = is_null($schedule) ? __("success_sent") : __("success_scheduled");
                echo json_encode(array(
                    'result' => $success
                ));
            }
        }
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => nl2br(htmlentities($t->getMessage(), ENT_QUOTES))
    ));
}