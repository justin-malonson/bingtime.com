<?php

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (!empty($_POST["name"]) && !empty($_POST["contactsListID"])) {
        $count = ContactsList::where('ID', $_POST["contactsListID"], '!=')
            ->where('userID', $_SESSION["userID"])
            ->where('name', $_POST["name"])
            ->count();
        if ($count > 0) {
            throw new Exception(__("error_contacts_list_exist"));
        } else {
            $contactsList = new ContactsList();
            $contactsList->setID($_POST["contactsListID"]);
            $contactsList->setUserID($_SESSION["userID"]);
            if ($contactsList->read()) {
                $contactsList->setName($_POST["name"]);
                $contactsList->save();
                echo json_encode([
                    "result" => __("success_contacts_list_updated"),
                ]);
            }
        }
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}
