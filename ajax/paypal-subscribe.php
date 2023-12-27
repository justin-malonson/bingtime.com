<?php
/**
 * @var User $logged_in_user
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (!empty($_POST["subscriptionID"])) {
        $details = PayPal::getSubscriptionDetails($_POST["subscriptionID"]);
        $plan = new Plan();
        $plan->setPaypalPlanID($details->plan_id);
        if ($plan->read()) {
            MysqliDb::getInstance()->startTransaction();
            $subscription = new Subscription();
            $subscription->setUserID($logged_in_user->getID());
            $subscription->setSubscriptionID($details->id);
            $subscription->setPlanID($plan->getID());
            $subscription->setPlan($plan);
            $subscription->setSubscribedDate(date("Y-m-d H:i:s", strtotime($details->create_time)));
            //$expiryDate = date("Y-m-d H:i:s", strtotime($details->billing_info->next_billing_time));
            $expiryDate = date("Y-m-d H:i:s", strtotime($details->create_time) + $plan->getFrequencyInSeconds());
            $subscription->setExpiryDate($expiryDate);
            $subscription->setPaymentMethod("PayPal");
            $subscription->setCyclesCompleted(1);
            $subscription->setStatus("ACTIVE");
            $subscription->save();
            DeviceUser::toggleDemoDevices($subscription->getUserID());
            $subscription->renew($expiryDate);
            MysqliDb::getInstance()->commit();
            echo json_encode([
                'result' => __("success_subscribed")
            ]);
        }
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}