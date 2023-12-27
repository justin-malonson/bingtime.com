<?php

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (isset($_POST["numbers"])) {
        // https://stackoverflow.com/a/7058270/1273550
        $numbers = preg_split('/\r\n|[\r\n]/', $_POST['numbers']);
        $count = 0;
        MysqliDb::getInstance()->startTransaction();
        foreach ($numbers as $number) {
            if (isValidMobileNumber($number)) {
                $entry = new Blacklist();
                $entry->setNumber($number);
                if ($_SESSION["isAdmin"] && isset($_GET["user"])) {
                    $entry->setUserID($_GET["user"]);
                } else {
                    $entry->setUserID($_SESSION["userID"]);
                }
                if (!$entry->read()) {
                    $entry->save();
                    $count++;
                }
            }
        }
        MysqliDb::getInstance()->commit();
        if ($count > 0) {
            echo json_encode([
                "result" => __("success_add_to_blacklist")
            ]);
        } else {
            throw new Exception(__("error_no_valid_numbers_found"));
        }
    } else {
        throw new Exception(__("error_missing_fields"));
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}
