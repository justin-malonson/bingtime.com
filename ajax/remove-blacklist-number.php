<?php

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (isset($_POST["numbers"]) && is_array($_POST["numbers"])) {
        $ids = $_POST["numbers"];
        $count = count($ids);
        if ($count > 0) {
            MysqliDb::getInstance()->startTransaction();
            foreach ($ids as $id) {
                $entry = new Blacklist();
                $entry->setID($id);
                if (!$_SESSION["isAdmin"]) {
                    $entry->setUserID($_SESSION["userID"]);
                }
                if ($entry->read()) {
                    $entry->delete();
                }
            }
            MysqliDb::getInstance()->commit();
            $success = $count > 1 ? __("success_numbers_removed", ["count" => $count]) : __("success_number_removed", ["count" => $count]);
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