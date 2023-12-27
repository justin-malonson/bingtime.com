<?php

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    $payments = [];
    if ($_SESSION["isAdmin"]) {
        $payments = Payment::read_all();
    } else {
        $payments = Payment::where("Payment.userID", $_SESSION["userID"])->read_all();
    }

    $data = [];
    foreach ($payments as $payment) {
        $row = [];
        $row[] = $payment->getTransactionID();
        if ($_SESSION["isAdmin"] && $payment->getStatus() === "COMPLETED") {
            $row[] = sprintf("%s&nbsp;<a href=\"#\" class=\"refund-payment\" style=\"color: red\" data-id=\"{$payment->getID()}\" title=\"%s\"><i class=\"fa fa-remove\"></i></a>", $payment->getStatus(), __("refund"));
        } else {
            $row[] = $payment->getStatus();
        }
        $row[] = "{$payment->getAmount()} {$payment->getCurrency()}";
        $row[] = "{$payment->getTransactionFee()} {$payment->getCurrency()}";
        $row[] = $payment->getSubscription()->getSubscriptionID();
        $row[] = $payment->getSubscription()->getPaymentMethod();
        $row[] = $payment->getDateAdded()->format("Y-m-d H:i:s");

        $data[] = $row;
    }

    echo json_encode([
        "data" => $data
    ]);
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}