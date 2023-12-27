<?php
/**
 * @var User $logged_in_user
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (!empty($_POST["subscriptionID"])) {
        if (ctype_digit($_POST["subscriptionID"])) {
            $subscription = new Subscription();
            $subscription->setID($_POST["subscriptionID"]);
            if (!$_SESSION["isAdmin"]) {
                $subscription->setUserID($logged_in_user->getID());
            }
            if ($subscription->read()) {
                $subscription->cancel();
                echo json_encode([
                    'result' => __("success_subscription_canceled"),
                ]);
            } else {
                throw new Exception(__("error_subscription_not_found"));
            }
        }
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}