<?php
/**
 * @var User $logged_in_user
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (isset($_POST["messages"])) {
        $ids = json_decode($_POST["messages"]);
        if (!empty($ids)) {
            $messages = array();
            $count = 0;
            foreach ($ids as $messageID) {
                $message = new Message();
                $message->setID($messageID);
                $message->read(false);
                $allowed = ["Failed", "Pending", "Queued", "Sent", "Delivered", "Canceled"];
                if (in_array($message->getStatus(), $allowed)) {
                    if (!$_SESSION["isAdmin"]) {
                        if ($message->getUserID() != $_SESSION["userID"]) {
                            continue;
                        }
                    }
                    if (array_key_exists($message->getUserID(), $messages)) {
                        $messages[$message->getUserID()]["messages"][] = $message;
                    } else {
                        if ($message->getUserID() != $logged_in_user->getID()) {
                            $user = new User();
                            $user->setID($message->getUserID());
                            $user->read();
                        } else {
                            $user = $logged_in_user;
                        }
                        $messages[$message->getUserID()] = [
                            "user" => $user,
                            "messages" => [$message]
                        ];
                    }
                }
            }

            foreach ($messages as $userID => $userMessages) {
                if (count($userMessages["messages"]) > 0) {
                    Message::resend($userMessages["messages"], $userMessages["user"]);
                    $count += count($userMessages["messages"]);
                }
            }
            if ($count > 0) {
                $success = $count > 1 ? __("success_messages_sent", ["count" => $count]) : __("success_message_sent", ["count" => $count]);
                echo json_encode(array(
                    'result' => $success
                ));
            } else {
                throw new Exception(__("error_zero_messages"));
            }
        }
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}