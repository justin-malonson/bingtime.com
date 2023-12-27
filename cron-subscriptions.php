<?php

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/includes/set-language.php";

date_default_timezone_set(TIMEZONE);

try {
    MysqliDb::getInstance()->startTransaction();
    $subscriptions = Subscription::where("Subscription.status", "ACTIVE")->read_all();
//    $admin = User::getAdmin();
//    $from = array($admin->getEmail(), $admin->getName());
    foreach ($subscriptions as $subscription) {
        $renew = $subscription->getPlan()->getTotalCycles() == 0 || $subscription->getCyclesCompleted() < $subscription->getPlan()->getTotalCycles();
//        $secondsTillRenew = (new DateTime())->getTimestamp() - $subscription->getExpiryDate()->getTimestamp();
//        if ($renew && $subscription->getPaymentMethod() == "PayPal" && $secondsTillRenew >= 259200 && $secondsTillRenew <= 259259) {
//            $to = array($subscription->getUser()->getEmail(), $subscription->getUser()->getName());
//            sendEmail($from, $to, "Subscription Renewal", "Your subscription will be renewed in 3 days and you will be charged {$subscription->getPlan()->getPrice()} {$subscription->getPlan()->getCurrency()}}.");
//        }
        if ($subscription->getExpiryDate() < new DateTime()) {
            if ($renew) {
                $subscription->setCyclesCompleted($subscription->getCyclesCompleted() + 1);
                $expiryDate = date("Y-m-d H:i:s", $subscription->getExpiryDate()->getTimestamp() + $subscription->getPlan()->getFrequencyInSeconds());
                $subscription->setExpiryDate($expiryDate);
                $subscription->save();
                $subscription->renew($expiryDate);
            } else {
                $subscription->setStatus("EXPIRED");
                $subscription->save();
            }
        }
    }
    MysqliDb::getInstance()->commit();
} catch (Exception $e) {
    error_log($e->getMessage());
}
