<?php
$time_start = microtime(true);

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../vendor/autoload.php";

date_default_timezone_set(TIMEZONE);
set_time_limit(60);

try {
    if (isset($_POST["androidId"]) && isset($_POST["userId"]) && isset($_POST["messages"])) {
        MysqliDb::getInstance()->startTransaction();
        $device = new Device();
        $device->setAndroidID($_POST["androidId"]);
        $device->setUserID($_POST["userId"]);
        if ($device->read()) {
            $messages = json_decode($_POST["messages"], true);
            $messageObjects = [];
            $sendMessages = [];
            if ($messages) {
                $numbers = [];
                $responses = Response::where('userID', $device->getUserID())
                    ->where("enabled", "1")
                    ->read_all();
                foreach ($messages as $msg) {
                    if (Setting::get("use_credits_for_received_messages_enabled")) {
                        if (!is_null($device->getUser()->getCredits()) && $device->getUser()->getCredits() <= count($messageObjects)) {
                            continue;
                        }
                    }
                    $message = new Message();
                    $message->setNumber($msg["number"]);
                    $message->setMessage($msg["message"]);
                    if (isset($msg["simSlot"]) && $msg["simSlot"] != -1) {
                        $message->setSimSlot($msg["simSlot"]);
                    }
                    $message->setDeviceID($device->getID());
                    $message->setUserID($_POST["userId"]);
                    if (isset($msg["sentDate"])) {
                        $sentDate = new DateTime($msg["sentDate"]);
                        $sentDate->setTimezone(new DateTimeZone(TIMEZONE));
                        $message->setSentDate($sentDate->format("Y-m-d H:i:s"));
                    }
                    $receivedDate = new DateTime($msg["receivedDate"]);
                    $receivedDate->setTimezone(new DateTimeZone(TIMEZONE));
                    $message->setDeliveredDate($receivedDate->format("Y-m-d H:i:s"));
                    $message->setStatus("Received");
                    $message->save();
                    $numbers[] = $message->getNumber();
                    $messageObjects[] = $message;
                    if (isValidMobileNumber($message->getNumber())) {
                        /*
                        if (strtolower($message->getMessage()) === "start") {
                            $entry = new Blacklist();
                            $entry->setNumber($message->getNumber());
                            $entry->setUserID($device->getUserID());
                            if ($entry->read()) {
                                $entry->delete();
                            }
                        }
                        */
                        if (strtolower(substr($message->getMessage(), 0, 4)) === "stop") {
                            $parts = explode(" ", $message->getMessage());
                            $entry = new Blacklist();
                            $entry->setNumber($message->getNumber());
                            if (count($parts) === 1) {
                                $entry->setUserID($device->getUserID());
                                if (!$entry->read()) {
                                    $entry->save();
                                    $sendMessages[] = [
                                        "number" => $message->getNumber(),
                                        "simSlot" => $message->getSimSlot(),
                                        "message" => __("success_blacklisted"),
                                        "ignoreBlacklist" => true
                                    ];
                                }
                            } else if (count($parts) === 2 && ctype_digit($parts[1])) {
                                if (DeviceUser::where('DeviceUser.deviceID', $device->getID())
                                        ->where('DeviceUser.userID', $parts[1])
                                        ->where('DeviceUser.active', true)
                                        ->count() > 0
                                ) {
                                    $entry->setUserID($parts[1]);
                                    if (!$entry->read()) {
                                        $entry->save();
                                        $sendMessages[] = [
                                            "number" => $message->getNumber(),
                                            "simSlot" => $message->getSimSlot(),
                                            "message" => __("success_blacklisted"),
                                            "ignoreBlacklist" => true
                                        ];
                                    }
                                }
                            }
                        }
                        else if (strtolower(substr($message->getMessage(), 0, 11)) === "unsubscribe") {
                            $parts = explode(" ", $message->getMessage());
                            if (count($parts) === 2 && ctype_digit($parts[1])) {
                                $contact = new Contact();
                                $contact->setNumber($message->getNumber());
                                $contact->setContactsListID($parts[1]);
                                if ($contact->read()) {
                                    $contact->setSubscribed(false);
                                    $contact->save();
                                    $sendMessages[] = [
                                        "number" => $message->getNumber(),
                                        "simSlot" => $message->getSimSlot(),
                                        "message" => __("success_unsubscribed")
                                    ];
                                }
                            }
                        }
                        foreach ($responses as $response) {
                            $result = $response->match($message->getMessage());
                            if ($result) {
                                $sendMessages[] = [
                                    "number" => $message->getNumber(),
                                    "simSlot" => $message->getSimSlot(),
                                    "message" => $response->getResponse()
                                ];
                            }
                        }
                    }
                }

                try {
                    if (Setting::get("sms_to_email_enabled") && $device->getUser()->getSmsToEmail() && !empty($messageObjects)) {
                        $contacts = $device->getUser()->getContacts($numbers);
                        $receivedMessages = [];
                        $simObjects = Sim::where("deviceID", $device->getID())->read_all();
                        $sims = [];
                        foreach ($simObjects as $simObj) {
                            $sims[$simObj->getSlot()] = strval($simObj);
                        }

                        foreach ($messageObjects as $message) {
                            $receivedMessages[] = [
                                "number" => isset($contacts[$message->getNumber()]) ? $contacts[$message->getNumber()] . " ({$message->getNumber()})" : $message->getNumber(),
                                "message" => $message->getMessage(),
                                "device" => strval($device),
                                "sim" => $message->getSimSlot() != null ? " ({$sims[$message->getSimSlot()]})" : "",
                                "status" => "Received"
                            ];
                        }

                        $admin = User::getAdmin();
                        $from = array($admin->getEmail(), $admin->getName());
                        $to = [
                            $device->getUser()->getReceivedSmsEmail() ?: $device->getUser()->getEmail(),
                            $device->getUser()->getName()
                        ];
                        foreach ($receivedMessages as $receivedMessage) {
                            Job::queue("sendEmail", [$from, $to, "Message from {$receivedMessage["number"]} on {$receivedMessage["device"]} ({$receivedMessage["sim"]})", $receivedMessage["message"]]);
                        }
                    }
                } catch (Exception $t) {
                    error_log($t->getMessage());
                }
                if (Setting::get("use_credits_for_received_messages_enabled")) {
                    $device->getUser()->depleteCredits(count($messageObjects));
                }
            }
            MysqliDb::getInstance()->commit();
            try {
                if (!empty($sendMessages)) {
                    Message::sendMessages($sendMessages, $device->getUser(), [$device->getID()], null, 1);
                }
            } catch (Exception $exception) {
                error_log($exception);
            }
            if (!empty($messageObjects)) {
                $device->getUser()->callWebhook('messages', $messageObjects);
            }
            echo json_encode(["success" => true, "data" => null, "error" => null]);
        } else {
            echo json_encode(["success" => false, "data" => null, "error" => ["code" => 401, "message" => __("error_device_not_found")]]);
        }
    } else {
        throw new Exception(__("error_invalid_request_format"));
    }
} catch (Throwable $t) {
    echo json_encode(["success" => false, "data" => null, "error" => ["code" => 500, "message" => $t->getMessage()]]);
}

$time_end = microtime(true);
$execution_time = $time_end - $time_start;
//error_log("Total Execution Time: {$execution_time} seconds");