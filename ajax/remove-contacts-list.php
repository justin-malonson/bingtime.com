<?php

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (isset($_POST["id"]) && ctype_digit($_POST["id"])) {
        $contactsList = ContactsList::getContactsList($_POST["id"], $_SESSION["userID"]);
        if ($contactsList) {
            $contactsList->delete();
            echo json_encode(array(
                'result' => __("success_contacts_list_removed")
            ));
        }
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}