<?php

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    $templates = Template::where('userID', $_SESSION["userID"])->read_all();
    $data = [];
    foreach ($templates as $template) {
        $row = [];
        $row[] = "<label><input type='checkbox' name='templates[]' class='remove-templates' onchange='toggleRemove()' value='{$template->getID()}'></label>";
        $row[] = "<a href=\"#\" class=\"edit-template\" data-id=\"{$template->getID()}\" data-name=\"{$template->getName()}\" data-message=\"{$template->getMessage()}\">{$template->getName()}</a>";
        $row[] = nl2br($template->getMessage());
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
