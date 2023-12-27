<?php

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (isset($_REQUEST["contactsListID"]) && ctype_digit($_REQUEST["contactsListID"])) {
        if (ContactsList::getContactsList($_REQUEST["contactsListID"], $_SESSION["userID"])) {
            Contact::setPageLimit($_REQUEST["length"]);
            $page = 1;
            if ($_REQUEST["start"] != 0) {
                $page = (($_REQUEST["start"] - 1) / $_REQUEST["length"]) + 1;
            }
            $contacts = Contact::where('contactsListID', $_REQUEST["contactsListID"]);
            if (!empty($_REQUEST["search"]["value"])) {
                $searchTerm = $_REQUEST["search"]["value"];
                $contacts->where('(name LIKE ? OR number LIKE ?)', ["%{$searchTerm}%", "%{$searchTerm}%"]);
            }
            if (isset($_GET["order"][0])) {
                $fields = [
                    1 => "name",
                    2 => "number",
                    3 => "subscribed"
                ];
                if (isset($fields[$_GET["order"][0]["column"]])) {
                    $contacts->orderBy($fields[$_GET["order"][0]["column"]], strtoupper($_GET["order"][0]["dir"]));
                }
            }
            $contacts = $contacts->read_all(false, $page);
            $data = [];
            foreach ($contacts as $contact) {
                $row = [];
                $row[] = "<label><input type='checkbox' name='contacts[]' class='remove-contacts' onchange='toggleOptions()' value='{$contact->getID()}'></label>";
                $row[] = htmlentities($contact->getName() ?? '', ENT_QUOTES);
                $row[] = $contact->getNumber();
                $row[] = $contact->getSubscribed() ? "<i class='fa fa-check'></i>" : "<i class='fa fa-close'></i>";
                $data[] = $row;
            }

            echo json_encode([
                'draw' => (int)$_REQUEST["draw"],
                'recordsFiltered' => Contact::getTotalCount(),
                'recordsTotal' => Contact::where('contactsListID', $_REQUEST["contactsListID"])->count(),
                'data' => $data
            ]);
        }
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}
