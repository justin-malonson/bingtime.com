<?php
/**
 * @var User $logged_in_user
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (isset($_POST["messages"])) {
        $messages = json_decode($_POST["messages"]);
        if (!empty($messages)) {
            $creditsRefunds = [];
            MysqliDb::getInstance()->startTransaction();
            foreach ($messages as $messageID) {
                $message = new Message();
                $message->setID($messageID);
                if ($message->read()) {
                    $message->delete();
                    if ($message->getStatus() == "Scheduled") {
                        if ($message->getExpiryDate() === null || $message->getExpiryDate() >= new DateTime()) {
                            $credits = countMessageCredits($message->getNumber(), $message->getMessage(), $message->getType());
                            if (isset($creditsRefunds[$message->getUserID()])) {
                                $creditsRefunds[$message->getUserID()]["credits"] += $credits;
                            } else {
                                if ($message->getUserID() == $logged_in_user->getID()) {
                                    $user = $logged_in_user;
                                } else {
                                    $user = new User();
                                    $user->setID($message->getUserID());
                                    $user->read();
                                }
                                $creditsRefunds[$message->getUserID()] = [
                                    "user" => $user,
                                    "credits" => $credits
                                ];
                            }
                        }
                    }
                }
            }
            foreach ($creditsRefunds as $creditsRefund) {
                $user = $creditsRefund["user"];
                if (!is_null($user->getCredits())) {
                    $user->setCredits($user->getCredits() + $creditsRefund["credits"]);
                    $user->save();
                }
            }
            MysqliDb::getInstance()->commit();
            $count = count($messages);
            $success = $count > 1 ? __("success_messages_removed", ["count" => $count]) : __("success_message_removed", ["count" => $count]);
            echo json_encode(array(
                'result' => $success
            ));
        }
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}