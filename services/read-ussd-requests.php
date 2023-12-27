<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../vendor/autoload.php";

date_default_timezone_set(TIMEZONE);

try {
    require_once __DIR__ . "/../includes/get-user.php";
    if (isset($user)) {
        if ($user) {
            if ($user->getIsAdmin()) {
                $ussdRequests = new Ussd();
            } else {
                $ussdRequests = Ussd::where("Ussd.userID", $user->getID());
            }
            if (isset($_REQUEST["id"])) {
                $ussdRequests = $ussdRequests->where("Ussd.ID", $_REQUEST["id"]);
            }
            if (isset($_REQUEST["request"])) {
                $ussdRequests = $ussdRequests->where("Ussd.request", $_REQUEST["request"]);
            }
            if (isset($_REQUEST["deviceID"])) {
                $ussdRequests = $ussdRequests->where("Ussd.deviceID", $_REQUEST["deviceID"]);
            }
            if (isset($_REQUEST["simSlot"])) {
                $ussdRequests = $ussdRequests->where("Ussd.simSlot", $_REQUEST["simSlot"]);
            }
            if (isset($_REQUEST["startTimestamp"])) {
                $ussdRequests = $ussdRequests->where("Ussd.sentDate", date("Y-m-d H:i:s", $_REQUEST["startTimestamp"]), ">=");
            }
            if (isset($_REQUEST["endTimestamp"])) {
                $ussdRequests = $ussdRequests->where("sentDate", date("Y-m-d H:i:s", $_REQUEST["endTimestamp"]), "<=");
            }
            $ussdRequests = $ussdRequests->read_all(false);

            if (count($ussdRequests) > 0) {
                echo json_encode(["success" => true, "data" => ["requests" => $ussdRequests], "error" => null]);
            } else {
                echo json_encode(["success" => false, "data" => null, "error" => ["code" => 404, "message" => __("no_ussd_requests_found")]]);
            }
        } else {
            echo json_encode(["success" => false, "data" => null, "error" => ["code" => 401, "message" => isset($_REQUEST["key"]) ? __("error_incorrect_api_key") : __("error_incorrect_credentials")]]);
        }
        die;
    }
    echo json_encode(["success" => false, "data" => null, "error" => ["code" => 400, "message" => __("error_invalid_request_format")]]);
} catch (Throwable $t) {
    echo json_encode(["success" => false, "data" => null, "error" => ["code" => 500, "message" => $t->getMessage()]]);
}
