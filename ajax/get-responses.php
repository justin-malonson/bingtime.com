<?php

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    $responses = Response::where("userID", $_SESSION["userID"])->read_all();

    $data = [];
    $matchTypes = [
        0 => __("exact_case_insensitive"),
        1 => __("exact_case_sensitive"),
        2 => __("contains"),
        3 => __("regular_expression"),
    ];
    foreach ($responses as $response) {
        $message = htmlentities($response->getMessage(), ENT_QUOTES);
        $responseText = htmlentities($response->getResponse(), ENT_QUOTES);
        $messageText = nl2br($message);
        $row = [];
        $row[] = "<label><input type='checkbox' name='responses[]' class='remove-responses' onchange='toggleRemove()' value='{$response->getID()}'></label>";
        $row[] = "<a href=\"#\" class=\"edit-response\" data-id=\"{$response->getID()}\" data-message=\"{$message}\" data-response=\"{$responseText}\" data-match-type=\"{$response->getMatchType()}\" data-enabled=\"{$response->getEnabled()}\">{$messageText}</a>";
        $row[] = nl2br($responseText);
        $row[] = $matchTypes[$response->getMatchType()];
        $row[] = $response->getEnabled() == 1 ? __("yes") : __("no");

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