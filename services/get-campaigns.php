<?php

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../vendor/autoload.php";

date_default_timezone_set(TIMEZONE);

try {
    if (isset($_POST["androidId"]) && isset($_POST["userId"])) {
        $db = MysqliDb::getInstance();
        $device = new Device();
        $device->setAndroidID($_POST["androidId"]);
        $device->setUserID($_POST["userId"]);
        if ($device->read()) {
            $ussdRequests = Ussd::where('Ussd.responseDate', null, 'IS')
                ->where('Ussd.userID', $_POST["userId"])
                ->where('Ussd.deviceID', $device->getID())
                ->orderBy('Ussd.id', 'ASC')
                ->read_all(false);
            if (isset($_POST["versionCode"]) && $_POST["versionCode"] >= 32) {
                $rows = $db->rawQuery("SELECT DISTINCT groupID, prioritize, userID FROM Message WHERE status='Pending' AND deviceID={$device->getID()}");
                $data = [];
                foreach ($rows as $row) {
                    $userID = $row["userID"];
                    if (isset($data[$userID])) {
                        if ($row["prioritize"]) {
                            $data[$userID]["prioritizedCampaigns"][] = $row["groupID"];
                        } else {
                            $data[$userID]["campaigns"][] = $row["groupID"];
                        }
                    } else {
                        $data[$userID] = [];
                        $user = new User();
                        $user->setID($userID);
                        $user->read();
                        if ($device->getUseOwnerSettings() && $user->getID() != $device->getUserID()) {
                            $data[$userID]["user"] = $device->getUser();
                            $data[$userID]["user"]->setSleepTime($user->getSleepTime());
                        } else {
                            $data[$userID]["user"] = $user;
                        }
                        if ($row["prioritize"]) {
                            $data[$userID]["prioritizedCampaigns"] = [$row["groupID"]];
                        } else {
                            $data[$userID]["campaigns"] = [$row["groupID"]];
                        }
                    }
                }
                if (isset($data[$device->getUserID()])) {
                    $data[$device->getUserID()]["ussdRequests"] = $ussdRequests;
                } else {
                    $data[$device->getUserID()] = [
                        "campaigns" => [],
                        "prioritizedCampaigns" => [],
                        "user" => $device->getUser(),
                        "ussdRequests" => $ussdRequests
                    ];
                }
                echo json_encode([
                    "success" => true,
                    "data" => ["userCampaigns" => array_values($data)],
                    "error" => null
                ]);
                die();
            }
            $rows = $db->rawQuery("SELECT DISTINCT groupID, prioritize FROM Message WHERE status='Pending' AND deviceID={$device->getID()}");
            $normalCampaigns = [];
            $prioritizedCampaigns = [];
            foreach ($rows as $row) {
                if ($row["prioritize"]) {
                    $prioritizedCampaigns[] = $row["groupID"];
                } else {
                    $normalCampaigns[] = $row["groupID"];
                }
            }
            if (isset($_POST["versionCode"]) && $_POST["versionCode"] >= 26) {
                echo json_encode([
                    "success" => true,
                    "data" => [
                        "campaigns" => $normalCampaigns,
                        "prioritizedCampaigns" => $prioritizedCampaigns,
                        "ussdRequests" => $ussdRequests,
                        "user" => $device->getUser()
                    ],
                    "error" => null
                ]);
            } else {
                echo json_encode(["success" => true, "data" => ["campaigns" => array_merge($prioritizedCampaigns, $normalCampaigns), "user" => $device->getUser()], "error" => null]);
            }
        }
    }
} catch (Throwable $t) {
    echo json_encode(["success" => false, "data" => null, "error" => ["code" => 500, "message" => $t->getMessage()]]);
}