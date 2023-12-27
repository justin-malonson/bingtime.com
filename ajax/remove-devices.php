<?php

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (isset($_POST["devices"]) && is_array($_POST["devices"])) {
        $deviceIDs = $_POST["devices"];
        $count = count($deviceIDs);
        if ($count > 0) {
            MysqliDb::getInstance()->startTransaction();
            foreach ($deviceIDs as $deviceID) {
                $device = new Device();
                $device->setID($deviceID);
                if (!$_SESSION["isAdmin"]) {
                    $device->setUserID($_SESSION["userID"]);
                }
                if ($device->read()) {
                    $device->delete();
                }
            }
            MysqliDb::getInstance()->commit();
            $success = $count > 1 ? __("success_devices_removed", ["count" => $count]) : __("success_device_removed", ["count" => $count]);
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