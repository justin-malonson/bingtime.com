<?php

try {
    require_once __DIR__ . "/../config.php";
    require_once __DIR__ . "/../vendor/autoload.php";
    date_default_timezone_set(TIMEZONE);

    $body = json_decode(file_get_contents('php://input'));
    //file_put_contents(__DIR__ . "/subscription-logs.txt", json_encode($body, JSON_PRETTY_PRINT), FILE_APPEND);

    $subscription = new Subscription();
    $subscription->setSubscriptionID($body->resource->id);
    if ($subscription->read()) {
        MysqliDb::getInstance()->startTransaction();

        /*
        $cycle_executions = $body->resource->billing_info->cycle_executions;
        foreach ($cycle_executions as $cycle_execution) {
            if ($cycle_execution->tenure_type !== "REGULAR") {
                continue;
            }
            if ($subscription->getCyclesCompleted() > $cycle_execution->cycles_completed) {
                $user = new User();
                $user->setID($subscription->getUserID());
                $extraCycles = $subscription->getCyclesCompleted() - $cycle_execution->cycles_completed;
                $expiryDate = date("Y-m-d H:i:s", $subscription->getExpiryDate()->getTimestamp() - ($extraCycles * $subscription->getPlan()->getFrequencyInSeconds()));
                $user->setExpiryDate($expiryDate);
                $user->save();
                $subscription->setCyclesCompleted($cycle_execution->cycles_completed);
                $subscription->setExpiryDate($expiryDate);
            }
            break;
        }
        */

        $objects = Payment::where("Payment.subscriptionID", $subscription->getID())->read_all();
        $payments = [];
        foreach ($objects as $object) {
            $payments[$object->getTransactionID()] = $object;
        }
        $startTime = $subscription->getSubscribedDate()->setTimezone(new DateTimeZone("UTC"));
        $endTime = new DateTime("now", new DateTimeZone("UTC"));
        $transactions = PayPal::getSubscriptionTransactions($subscription->getSubscriptionID(), $startTime, $endTime);
        $completedTransactions = 0;
        foreach ($transactions as $transaction) {
            if ($transaction->status === "COMPLETED") {
                $completedTransactions++;
            }
            if (isset($payments[$transaction->id])) {
                $payment = $payments[$transaction->id];
                if ($payment->getStatus() !== $transaction->status) {
                    $payment->setStatus($transaction->status);
                    $payment->save();
                }
            } else {
                $payment = new Payment();
                $payment->setTransactionID($transaction->id);
                $payment->setSubscriptionID($subscription->getID());
                $payment->setUserID($subscription->getUserID());
                $payment->setAmount($transaction->amount_with_breakdown->gross_amount->value);
                $payment->setStatus($transaction->status);
                $payment->setTransactionFee($transaction->amount_with_breakdown->fee_amount->value);
                $payment->setCurrency($transaction->amount_with_breakdown->gross_amount->currency_code);
                $payment->setDateAdded(date("Y-m-d H:i:s", strtotime($transaction->time)));
                $payment->save();
            }
        }

        if ($subscription->getCyclesCompleted() > $completedTransactions) {
            $user = new User();
            $user->setID($subscription->getUserID());
            $extraCycles = $subscription->getCyclesCompleted() - $completedTransactions;
            $expiryDate = date("Y-m-d H:i:s", $subscription->getExpiryDate()->getTimestamp() - ($extraCycles * $subscription->getPlan()->getFrequencyInSeconds()));
            $user->setExpiryDate($expiryDate);
            $user->save();
            $subscription->setCyclesCompleted($completedTransactions);
            $subscription->setExpiryDate($expiryDate);
        }

        if ($body->resource->status != 'CANCELLED') {
            $subscription->cancel("Payment failed");
        } else {
            $subscription->setStatus($body->resource->status);
            $subscription->save();
        }
        MysqliDb::getInstance()->commit();
    } else {
        error_log("Subscription id '{$body->resource->id}' doesn't exist in database!");
    }
} catch (Throwable $t) {
    error_log($t->getMessage());
    http_response_code(500);
    echo json_encode([
        "message" => $t->getMessage()
    ]);
}