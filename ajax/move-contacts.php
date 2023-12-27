<?php

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (isset($_POST["contacts"]) && is_array($_POST["contacts"]) && isset($_POST["listID"]) && ctype_digit($_POST["listID"])) {
        $ids = $_POST["contacts"];
        $count = count($ids);
        if ($count > 0) {
            MysqliDb::getInstance()->startTransaction();
            foreach ($ids as $id) {
                $contact = new Contact();
                $contact->setID($id);
                if ($contact->read() && $contact->getContactsList()->getUserID() == $_SESSION["userID"]) {
                    if ($contact->getContactsListID() == $_POST["listID"]) {
                        break;
                    }
                    $contact->setContactsListID($_POST["listID"]);
                    $contact->save();
                }
            }
            MysqliDb::getInstance()->commit();
            $success = $count > 1 ? __("success_contacts_moved", ["count" => $count]) : __("success_contact_moved", ["count" => $count]);
            echo json_encode(array(
                'result' => $success
            ));
        }
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}