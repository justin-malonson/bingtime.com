<?php

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (isset($_POST["templates"]) && is_array($_POST["templates"])) {
        $count = 0;
        MysqliDb::getInstance()->startTransaction();
        foreach ($_POST["templates"] as $templateID) {
            $template = new Template();
            $template->setID($templateID);
            $template->setUserID($_SESSION["userID"]);
            if ($template->read()) {
                $template->delete();
                $count++;
            }
        }
        MysqliDb::getInstance()->commit();
        $success = $count > 1 ? __("success_templates_removed", ["count" => $count]) : __("success_template_removed", ["count" => $count]);
        echo json_encode(array(
            'result' => $success
        ));
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}