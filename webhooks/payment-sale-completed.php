<?php

try {
    require_once __DIR__ . "/../config.php";
    require_once __DIR__ . "/../vendor/autoload.php";
    date_default_timezone_set(TIMEZONE);

    $input = file_get_contents('php://input');
    $body = json_decode($input);
    //file_put_contents(__DIR__ . "/payment-logs.txt", json_encode($body, JSON_PRETTY_PRINT), FILE_APPEND);

    if (isset($body->resource->billing_agreement_id)) {
        $subscription = new Subscription();
        $subscription->setSubscriptionID($body->resource->billing_agreement_id);
        if ($subscription->read()) {
            $payment = new Payment();
            $payment->setTransactionID($body->resource->id);
            if ($payment->read()) {
                $status = null;
                if ($body->event_type == "PAYMENT.SALE.REVERSED") {
                    $status = "REVERSED";
                } else if ($body->event_type == "PAYMENT.SALE.REFUNDED") {
                    $status = "REFUNDED";
                }
                if (empty($status)) {
                    error_log("Payment id '{$payment->getTransactionID()}' already exists!");
                } else {
                    $payment->setStatus($status);
                    $payment->save();
                }
            } else {
                $payment->setSubscriptionID($subscription->getID());
                $payment->setUserID($subscription->getUserID());
                $payment->setAmount($body->resource->amount->total);
                $payment->setStatus(strtoupper($body->resource->state));
                $payment->setTransactionFee($body->resource->transaction_fee->value);
                $payment->setCurrency($body->resource->amount->currency);
                $payment->setDateAdded(date("Y-m-d H:i:s", strtotime($body->resource->create_time)));
                $payment->save();
            }
        } else {
            error_log("Unable to recognize the transaction '{$body->resource->id}' for billing agreement '{$body->resource->billing_agreement_id}'.");
        }
    } else {
        error_log("There is no billing_agreement_id present in the request body. Request body value is {$input}.");
    }
} catch (Throwable $t) {
    error_log($t->getMessage());
    http_response_code(500);
    echo json_encode([
        "message" => $t->getMessage()
    ]);
}