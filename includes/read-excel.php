<?php

if (count(get_included_files()) == 1) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}

$tempFilePath = $_FILES['file']['tmp_name'];
$fileExtension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
$allowed_extensions = ["xls", "xlsx", "csv", "ods"];
if (!in_array($fileExtension, $allowed_extensions)) {
    exit(json_encode(
        array("error" => __("error_blocked_file_extension"))
    ));
}