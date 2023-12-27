<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../vendor/autoload.php";

date_default_timezone_set(TIMEZONE);

try {
    require_once __DIR__ . "/../includes/get-user.php";
    if (isset($user)) {
        if ($user) {
            if (isset($_REQUEST["number"]) && isset($_REQUEST["listID"])) {
                if (ContactsList::getContactsList($_REQUEST["listID"], $user->getID())) {
                    $resubscribe = false;
                    if (isset($_REQUEST["resubscribe"])) {
                        $resubscribe = $_REQUEST["resubscribe"];
                    }
                    $unsubscribe = false;
                    if (isset($_REQUEST["unsubscribe"])) {
                        $unsubscribe = $_REQUEST["unsubscribe"];
                    }
                    $contact = new Contact();
                    $contact->setNumber($_REQUEST["number"]);
                    $contact->setContactsListID($_REQUEST["listID"]);
                    if ($contact->read()) {
                        if ($unsubscribe == false && $resubscribe == false) {
                            echo json_encode(["success" => false, "data" => null, "error" => ["code" => 400, "message" => __("error_contact_exist")]]);
                            die;
                        }
                    } else {
                        if ($unsubscribe) {
                            echo json_encode(["success" => false, "data" => null, "error" => ["code" => 400, "message" => __("error_not_a_subscriber")]]);
                            die;
                        }
                    }
                    if (!empty($_REQUEST["name"])) {
                        $contact->setName($_REQUEST["name"]);
                    }
                    if ($unsubscribe) {
                        if ($contact->getSubscribed()) {
                            $contact->setSubscribed(false);
                        } else {
                            echo json_encode(["success" => false, "data" => null, "error" => ["code" => 400, "message" => __("error_already_unsubscribed")]]);
                            die;
                        }
                    } else {
                        $contact->setSubscribed(true);
                    }
                    $contact->save();
                    echo json_encode(["success" => true, "data" => ["contact" => $contact], "error" => null]);
                    die;
                } else {
                    echo json_encode(["success" => false, "data" => null, "error" => ["code" => 403, "message" => __("error_invalid_list_id")]]);
                    die;
                }
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
