<?php

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if ($_SESSION["isAdmin"]) {
        if (isset($_POST["users"]) && is_array($_POST["users"])) {
            $userIDs = $_POST["users"];
            $count = count($userIDs);
            if ($count > 0) {
                MysqliDb::getInstance()->startTransaction();
                foreach ($userIDs as $userID) {
                    $user = new User();
                    $user->setID($userID);
                    $user->delete();
                }
                MysqliDb::getInstance()->commit();
                $success = $count > 1 ? __("success_users_removed", ["count" => $count]) : __("success_user_removed", ["count" => $count]);
                echo json_encode(array(
                    'result' => $success
                ));
            }
        }
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}