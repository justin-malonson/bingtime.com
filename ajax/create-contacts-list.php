<?php
/**
 * @var User $logged_in_user
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (!empty($_POST["name"])) {
        $contactsList = new ContactsList();
        $contactsList->setName($_POST["name"]);
        $contactsList->setUserID($logged_in_user->getID());
        if (!$contactsList->read()) {
            $contactsList->save();
            echo json_encode([
                "result" => __("success_create_list")
            ]);
        } else {
            throw new Exception(__("error_contacts_list_exist"));
        }
    } else {
        throw new Exception(__("error_list_name_required"));
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}
