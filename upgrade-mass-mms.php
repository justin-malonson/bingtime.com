<?php

require_once __DIR__ . "/includes/login.php";

if (!$_SESSION["isAdmin"]) {
    die();
}

$db = MysqliDb::getInstance();
try {
    $db->rawQuery("ALTER TABLE Message MODIFY number text;");
    if ($db->getLastErrno()) {
        throw new Exception($db->getLastError());
    }
    echo "Successfully increased max number of recipients in a mass MMS.";
} catch (Exception $e) {
    echo $e->getMessage();
}