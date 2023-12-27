<?php

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if ($_SESSION["isAdmin"]) {
        if (isset($_POST["plans"]) && is_array($_POST["plans"])) {
            $count = 0;
            MysqliDb::getInstance()->startTransaction();
            foreach ($_POST["plans"] as $planID) {
                $plan = new Plan();
                $plan->setID($planID);
                if ($plan->read()) {
                    if ($plan->getEnabled() && !empty($plan->getPaypalPlanID())) {
                        try {
                            PayPal::deactivatePlan($plan->getPaypalPlanID());
                        } catch (Exception $e) {
                            // ignored
                        }
                    }
                    $plan->delete();
                    $count++;
                }
            }
            MysqliDb::getInstance()->commit();
            $success = $count > 1 ? __("success_plans_removed", ["count" => $count]) : __("success_plan_removed", ["count" => $count]);
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