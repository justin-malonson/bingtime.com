<?php

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    $entries = [];
    $userID = $_SESSION["userID"];
    if (isset($_GET["user"]) && $_GET["user"] != $_SESSION["userID"] && $_SESSION["isAdmin"]) {
        $userID = $_GET["user"];
    }
    $entries = Blacklist::where('userID', $userID)->read_all();
    $data = [];
    foreach ($entries as $entry) {
        $row = [];
        $row[] = "<label><input type='checkbox' name='numbers[]' class='remove-numbers' onchange='toggleRemove()' value='{$entry->getID()}'></label>";
        $row[] = $entry->getNumber();
        $data[] = $row;
    }

    echo json_encode([
        "data" => $data
    ]);
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}