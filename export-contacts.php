<?php

try {
    require_once __DIR__ . "/includes/login.php";

    if (isset($_REQUEST["listID"])) {
        $contactsList = ContactsList::getContactsList($_REQUEST["listID"], $_SESSION["userID"]);
        if ($contactsList) {
            $contacts = Contact::where("contactsListID", $contactsList->getID())->read_all(false);
            if (count($contacts) > 0) {
                objectsToExcel($contacts, "{$contactsList->getName()}.csv", ["name" => __("name"), "number" => __("mobile_number"), "subscribed" => __("subscribed")], array("ID", "contactsListID", "token", "contactsList"));
            } else {
                header("location:contacts.php");
            }
        }
    }
} catch (Exception $e) {
    echo json_encode(array(
        "error" => $e->getMessage()
    ));
}

