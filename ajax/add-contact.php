<?php
/**
 * @var User $logged_in_user
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (isset($_POST["listID"]) && ctype_digit($_POST["listID"])) {
        if (ContactsList::getContactsList($_POST["listID"], $_SESSION["userID"])) {
            $totalContacts = Contact::where('ContactsList.userID', $logged_in_user->getID())->count();
            if ($logged_in_user->getContactsLimit() !== null && $logged_in_user->getContactsLimit() <= $totalContacts) {
                throw new Exception(__("error_contacts_limit_reached"));
            }
            $number = sanitize($_POST["number"]);
            if (isValidMobileNumber($number)) {
                $contact = new Contact();
                $contact->setNumber($number);
                $contact->setContactsListID($_POST["listID"]);
                if (!$contact->read()) {
                    if (isset($_POST["name"])) {
                        $name = sanitize($_POST["name"]);
                        if ($name !== "") {
                            $contact->setName($name);
                        }
                    }
                    $contact->save();
                    echo json_encode(array(
                        'result' => __("success_new_contact")
                    ));
                } else {
                    throw new Exception(__("error_contact_exist"));
                }
            } else {
                throw new Exception(__('error_use_valid_number'));
            }
        }
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}