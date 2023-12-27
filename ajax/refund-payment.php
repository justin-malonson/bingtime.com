<?php
/**
 * @var User $logged_in_user
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if ($_SESSION["isAdmin"]) {
        if (!empty($_POST["paymentID"])) {
            $payment = new Payment();
            $payment->setID($_POST["paymentID"]);
            if ($payment->read()) {
                if ($payment->getStatus() === "COMPLETED") {
                    $payment->refund();
                    if ($payment->getSubscription()->getStatus() === "ACTIVE") {
                        $payment->getSubscription()->cancel("Payment refunded");
                    }
                    echo json_encode([
                        'result' => __("success_payment_refunded"),
                    ]);
                }
            } else {
                throw new Exception(__("error_payment_not_found"));
            }
        }
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}