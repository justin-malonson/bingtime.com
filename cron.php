<?php

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/vendor/autoload.php";

date_default_timezone_set(TIMEZONE);

$db = MysqliDb::getInstance();
$now = time();
$messageGroups = $db->rawQuery("SELECT DISTINCT groupID, userID, deviceID, expiryDate, prioritize FROM Message WHERE schedule <= {$now} AND status='Scheduled' GROUP BY groupID, userID, deviceID, expiryDate, prioritize");
Message::where("schedule", $now, '<=')
    ->where("status", "Scheduled")
    ->update_all(['status' => 'Pending', 'sentDate' => date("Y-m-d H:i:s")]);
foreach ($messageGroups as $group) {
    try {
        $groups = [];
        $deviceUser = User::getDeviceUser($group["deviceID"], $group["userID"]);
        if ($deviceUser->getDevice()->getUseOwnerSettings() && $deviceUser->getUserID() != $deviceUser->getDevice()->getUserID()) {
            $groups[$deviceUser->getDeviceID()] = ["device" => $deviceUser->getDevice(), "data" => ["groupId" => $group["groupID"], "delay" => $deviceUser->getDevice()->getUser()->getDelay(), "reportDelivery" => $deviceUser->getDevice()->getUser()->getReportDelivery(), "useProgressiveQueue" => $deviceUser->getDevice()->getUser()->isUseProgressiveQueue(), "sleepTime" => $deviceUser->getUser()->getSleepTime(), "prioritize" => $group["prioritize"]]];
        } else {
            $groups[$deviceUser->getDeviceID()] = ["device" => $deviceUser->getDevice(), "data" => ["groupId" => $group["groupID"], "delay" => $deviceUser->getUser()->getDelay(), "reportDelivery" => $deviceUser->getUser()->getReportDelivery(), "useProgressiveQueue" => $deviceUser->getUser()->isUseProgressiveQueue(), "sleepTime" => $deviceUser->getUser()->getSleepTime(), "prioritize" => $group["prioritize"]]];
        }
        Device::processRequests($groups);
    } catch (Exception $e) {
        Message::where("groupID", $group["groupID"])
            ->update_all(['status' => 'Failed']);
        if (empty($group["expiryDate"]) || new DateTime($group["expiryDate"]) >= new DateTime()) {
            $user = new User();
            $user->setID($group["userID"]);
            if ($user->read()) {
                if (!is_null($user->getCredits())) {
                    $data = $db->rawQuery("SELECT number, message, type FROM Message WHERE groupID = '{$group["groupID"]}' AND status = 'Failed'");
                    $credits = 0;
                    foreach ($data as $message) {
                        $credits += countMessageCredits($message["number"], $message["message"], $message["type"] ?? "sms");
                    }
                    $user->setCredits($user->getCredits() + $credits);
                    $user->save();
                }
            }
        }
        error_log($e->getMessage());
    }
}

$lastRetry = Setting::get("last_retry_timestamp") ? (int)Setting::get("last_retry_timestamp") : 0;
$retryTimeInterval = Setting::get("retry_time_interval") ? (int)Setting::get("retry_time_interval") : 900;
if ($now >= $lastRetry + $retryTimeInterval) {
    $currentTime = date("Y-m-d H:i:s", $now);
    $failedMessages = Message::where("status", "Failed")->where("sentDate >= DATE_SUB('{$currentTime}', INTERVAL {$retryTimeInterval} SECOND)")->read_all();
    //$queuedMessages = Message::where("status", "Queued")->where("TIMESTAMPDIFF(SECOND, deliveredDate, '{$currentTime}') >= {$retryTimeInterval}")->read_all();
    //$data = array_merge($failedMessages, $queuedMessages);
    $data = $failedMessages;
    $messages = [];
    foreach ($data as $message) {
        if (array_key_exists($message->getUserID(), $messages)) {
            $messages[$message->getUserID()][] = $message;
        } else {
            $messages[$message->getUserID()] = [$message];
        }
    }

    foreach ($messages as $userID => $userMessages) {
        $user = new User();
        $user->setID($userID);
        $user->read();
        if ($user->getAutoRetry()) {
            Message::resend($userMessages, $user, true);
        }
    }
    Setting::apply([
        "last_retry_timestamp" => $now
    ]);
}

$jobs = Job::read_all();
foreach ($jobs as $job) {
    try {
        $job->execute();
    } catch (Throwable $t) {
        error_log($t);
    } finally {
        $job->delete();
    }
}
