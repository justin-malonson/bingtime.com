<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../vendor/autoload.php";

date_default_timezone_set(TIMEZONE);

try {
    require_once __DIR__ . "/../includes/get-user.php";

    if (isset($user)) {
        if ($user) {
            $devices = [];
            if (isset($_REQUEST["option"]) && $_REQUEST["option"] >= 1) {
                $objects = $user->getDevices();
                $sims = $user->getSims();
                foreach ($objects as $object) {
                    if ($_REQUEST["option"] == 2 && isset($sims[$object->getDeviceID()]) && count($sims[$object->getDeviceID()]) > 1) {
                        foreach (array_keys($sims[$object->getDeviceID()]) as $simSlot) {
                            $devices[] = $object->getDeviceID() . "|" . $simSlot;
                        }
                    } else {
                        $devices[] = $object->getDeviceID();
                    }
                }
            } else if (!empty($_REQUEST["devices"])) {
                $devices = json_decode($_REQUEST["devices"], true);
                if (!is_array($devices)) {
                    $devices = [$_REQUEST["devices"]];
                }
            }
            if (isset($_REQUEST["useRandomDevice"]) && $_REQUEST["useRandomDevice"] && !empty($devices)) {
                $devices = array($devices[array_rand($devices, 1)]);
            }
            $attachments = null;
            $type = "sms";
            if (isset($_REQUEST["type"]) && ($_REQUEST["type"] === "sms" || $_REQUEST["type"] === "mms")) {
                $type = $_REQUEST["type"];
                if (isset($_REQUEST["attachments"]) && $type === "mms") {
                    $attachments = Message::isValidAttachments($_REQUEST["attachments"]);
                }
            }
            if (isset($_REQUEST["number"]) && isset($_REQUEST["message"])) {
                if (is_array($_REQUEST["number"])) {
                    $messages = [];
                    foreach ($_REQUEST["number"] as $number) {
                        $messages[] = [
                            "number" => $number,
                            "message" => $_REQUEST["message"],
                            "type" => $type,
                            "attachments" => $attachments
                        ];
                    }
                } else {
                    if (strpos($_REQUEST["number"], ',') !== false) {
                        $messages = [];
                        $numbers = explode(',', $_REQUEST["number"]);
                        foreach ($numbers as $number) {
                            $messages[] = [
                                "number" => $number,
                                "message" => $_REQUEST["message"],
                                "type" => $type,
                                "attachments" => $attachments
                            ];
                        }
                    } else {
                        $messages = array(
                            [
                                "number" => $_REQUEST["number"],
                                "message" => $_REQUEST["message"],
                                "type" => $type,
                                "attachments" => $attachments
                            ]
                        );
                    }
                }
            } else if (isset($_REQUEST["messages"])) {
                $messages = json_decode($_REQUEST["messages"], true);
                for ($i = 0; $i < count($messages); $i++) {
                    if (isset($messages[$i]["type"]) && $messages[$i]["type"] == "mms") {
                        if (!empty($messages[$i]["attachments"])) {
                            $messages[$i]["attachments"] = Message::isValidAttachments($_REQUEST["attachments"]);
                        }
                    }
                }
            } else if (isset($_REQUEST["listID"])) {
                if (ContactsList::getContactsList($_REQUEST["listID"], $user->getID())) {
                    $contacts = Contact::where("contactsListID", $_REQUEST["listID"])
                        ->where("subscribed", true)
                        ->read_all();
                    if (empty($contacts)) {
                        echo json_encode(["success" => false, "data" => null, "error" => ["code" => 204, "message" => __("error_no_subscribers")]]);
                        die;
                    } else {
                        $messages = [];
                        foreach ($contacts as $contact) {
                            $number = $contact->getNumber();
                            $message = $contact->getMessage($_REQUEST["message"]);
                            $messages[] = [
                                "number" => $number,
                                "message" => $message,
                                "type" => $type,
                                "attachments" => $attachments
                            ];
                        }
                    }
                } else {
                    echo json_encode(["success" => false, "data" => null, "error" => ["code" => 400, "message" => __("error_invalid_list_id")]]);
                    die;
                }
            } else {
                echo json_encode(["success" => true, "data" => ["credits" => $user->getCredits()], "error" => null]);
                die;
            }
            if (isset($messages)) {
                $schedule = null;
                if (isset($_REQUEST["schedule"])) {
                    $schedule = $_REQUEST["schedule"];
                }
                $prioritize = 0;
                if (isset($_REQUEST["prioritize"])) {
                    $prioritize = $_REQUEST["prioritize"];
                }
                $msgObjects = Message::sendMessages($messages, $user, $devices, $schedule, $prioritize);
                echo json_encode(["success" => true, "data" => ["messages" => $msgObjects], "error" => null]);
                die;
            }
        } else {
            echo json_encode(["success" => false, "data" => null, "error" => ["code" => 401, "message" => isset($_REQUEST["key"]) ? __("error_incorrect_api_key") : __("error_incorrect_credentials")]]);
            die;
        }
    }
    echo json_encode(["success" => false, "data" => null, "error" => ["code" => 400, "message" => __("error_invalid_request_format")]]);
} catch (Throwable $t) {
    echo json_encode(["success" => false, "data" => null, "error" => ["code" => 500, "message" => $t->getMessage()]]);
}