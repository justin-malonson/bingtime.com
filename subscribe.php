<?php
/**
 * @var User $logged_in_user
 */

require_once __DIR__ . "/includes/login.php";

$title = __("application_title") . " | " . __("subscribe");

$plans = Plan::where("Plan.enabled", "1")->read_all();
usort($plans, function ($a, $b) {
    return $a->getPrice() > $b->getPrice() ? 1 : -1;
});

if (!Setting::get("paypal_enabled") || count($plans) === 0) {
    exit();
}

$activeSubscription = Subscription::where("Subscription.status", "ACTIVE")->where('Subscription.UserID', $logged_in_user->getID())->read();

require_once __DIR__ . "/includes/header.php";
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= __("subscribe"); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php foreach ($plans as $plan) { ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <ul class="price">
                        <li class="header"><?= $plan->getName(); ?></li>
                        <?php
                        $frequencyUnit = ucfirst(__(strtolower($plan->getFrequencyUnit())));
                        $billingCycle = $plan->getFrequency() == 1 ? $frequencyUnit : "{$plan->frequency} {$frequencyUnit}"
                        ?>
                        <li class="grey"><?= "{$plan->getPrice()} {$plan->getCurrency()} / {$billingCycle}"; ?> </li>
                        <li><?= (is_null($plan->getCredits()) ? __("unlimited") : $plan->getCredits()) . " " . __("credits"); ?></li>
                        <li>
                        <?php
                            if (is_null($plan->getDevices())) {
                                echo __("unlimited") . " " . __("devices");
                            } else {
                                if ($plan->getDevices() > 0) {
                                    echo $plan->getDevices() . " " . __("devices");
                                } else {
                                    echo ucfirst(__("shared_devices"));
                                }
                            }
                        ?>
                        </li>
                        <li><?= (is_null($plan->getContacts()) ? __("unlimited") : $plan->getContacts()) . " " . __("contacts"); ?></li>
                        <?php $renewsUntil = $plan->getTotalCycles() > 0 ? $plan->getTotalCycles() * $plan->getFrequency() . " " . $frequencyUnit : __("cancelled"); ?>
                        <li><?= __("renews_until") . " {$renewsUntil}"; ?></li>
                        <li class="grey">
                            <?php if ($activeSubscription) { ?>
                                <?php if ($activeSubscription->getPlanID() == $plan->getID()) { ?>
                                    <button type="button" class="btn btn-lg btn-danger cancel-subscription"
                                            data-subscription-id="<?= $activeSubscription->getID() ?>"><i
                                                class="fa fa-remove"></i>&nbsp;<?= __("cancel") ?></button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-lg btn-primary" disabled><i
                                                class="fa fa-shopping-cart"></i>&nbsp;<?= __("buy_now") ?></button>
                                <?php } ?>
                            <?php } else { ?>
                                <div id="paypal-button-container-<?= $plan->getID() ?>"></div>
                            <?php } ?>
                        </li>
                    </ul>
                </div>
            <?php } ?>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require_once __DIR__ . "/includes/footer.php"; ?>

<?php if (Setting::get("paypal_client_id")) { ?>
    <?php if ($activeSubscription === false) { ?>
        <script src="https://www.paypal.com/sdk/js?client-id=<?= urlencode(Setting::get("paypal_client_id")); ?>&vault=true&intent=subscription"></script>

        <script>
            $(function () {
                const options = {positionClass: "toast-top-center", closeButton: true};

                <?php foreach ($plans as $plan) { ?>
                paypal.Buttons({
                    createSubscription: function (data, actions) {
                        return actions.subscription.create({
                            'plan_id': '<?= $plan->getPaypalPlanID() ?>'
                        });
                    },
                    onApprove: function (data, actions) {
                        let postData = { subscriptionID: data.subscriptionID };
                        let url = "ajax/paypal-subscribe.php";
                        ajaxRequest(url, postData).then(result => {
                            toastr.success(result, null, options);
                            setTimeout(() => {
                                location.reload();
                            }, 1000)
                        }).catch(reason => {
                            toastr.error(reason, null, options);
                        })
                    },
                    onError: function (err) {
                        toastr.error(err, null, options);
                    }
                }).render('#paypal-button-container-<?= $plan->getID() ?>');
                <?php } ?>
            });
        </script>
    <?php } else { ?>
        <script>
            $(function () {
                $(".cancel-subscription").click(function (event) {
                    event.preventDefault();
                    let result = confirm("<?=__("cancel_subscription_confirmation");?>");
                    if (result) {
                        let subscriptionId = $(this).data("subscription-id");
                        let postData = { subscriptionID: subscriptionId };
                        let url = "ajax/cancel-subscription.php";
                        $(this).prop('disabled', true);
                        ajaxRequest(url, postData).then(result => {
                            toastr.success(result);
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }).catch(reason => {
                            toastr.error(reason);
                        }).finally(() => {
                            $(this).prop('disabled', false);
                        });
                    }
                });
            });
        </script>
    <?php } ?>
<?php } ?>
</body>
</html>
