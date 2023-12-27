<?php

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if ($_SESSION["isAdmin"]) {
        if (isset($_POST["userID"]) && ctype_digit($_POST["userID"])) {
            $user = new User();
            $user->setID($_POST["userID"]);
            if ($user->read()) {
                $pastCredits = $user->getCredits();
                $pastDevicesLimit = $user->getDevicesLimit();
                $pastContactsLimit = $user->getContactsLimit();
                $pastExpiryDate = $user->getExpiryDate();
                if (isset($_POST["credits"])) {
                    if (ctype_digit($_POST["credits"])) {
                        $user->setCredits($_POST["credits"]);
                    } else {
                        throw new Exception(__("error_credits_not_number"));
                    }
                } else {
                    $user->setCredits(null);
                }
                if (isset($_POST["expiryDate"])) {
                    $user->setExpiryDate(getDatabaseTime($_POST["expiryDate"])->format("Y-m-d H:i:s"));
                } else {
                    $user->setExpiryDate(null);
                }
                if (isset($_POST["devicesLimit"])) {
                    if (ctype_digit($_POST["devicesLimit"])) {
                        $user->changeDevicesLimit($_POST["devicesLimit"]);
                    } else {
                        throw new Exception(__("error_max_devices_not_number"));
                    }
                } else {
                    $user->setDevicesLimit(null);
                }
                if (isset($_POST["contactsLimit"])) {
                    if (ctype_digit($_POST["contactsLimit"])) {
                        $user->changeContactLimit($_POST["contactsLimit"]);
                    } else {
                        throw new Exception(__("error_max_contacts_not_number"));
                    }
                } else {
                    $user->setContactsLimit(null);
                }
                if ($pastCredits != $user->getCredits() || $pastDevicesLimit != $user->getDevicesLimit() || $pastExpiryDate != $user->getExpiryDate() || $pastContactsLimit != $user->getContactsLimit()) {
                    MysqliDb::getInstance()->startTransaction();
                    $user->save(false);
                    $user->sendUpdatedLimitsEmail();
                    MysqliDb::getInstance()->commit();
                    echo json_encode(array(
                        'result' => __("success_edit_user", [
                            "name" => htmlentities($user->getName(), ENT_QUOTES),
                            "email" => $user->getEmail()
                        ])
                    ));
                } else {
                    echo json_encode(array(
                        'result' => __("error_nothing_to_update")
                    ));
                }
            }
        }
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}