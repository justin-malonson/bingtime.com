<?php
/**
 * @var int $activePlansCount
 */

require_once __DIR__ . "/includes/login.php";
require_once __DIR__ . "/includes/get-status.php";


$title = __("application_title") . ' | ' . __("dashboard");

$queryString = "";
if (isset($_COOKIE["DEVICE_ID"])) {
    if ($_SESSION["isAdmin"]) {
        $queryString .= "&user={$_SESSION["userID"]}";
    }
    $queryString .= "&device={$_COOKIE["DEVICE_ID"]}";
}

/** @var User $logged_in_user */
$credits = is_null($logged_in_user->getCredits()) ? "&infin;" : $logged_in_user->getCredits();
if ($logged_in_user->getExpiryDate() != null) {
    $currentTime = new DateTime("now", new DateTimeZone($_SESSION["timeZone"]));
    $expiryTime = getDisplayTime($logged_in_user->getExpiryDate());
    $expiresAfter = __("expired");
    if ($expiryTime > $currentTime) {
        $interval = $expiryTime->diff($currentTime);
        $day = $interval->format('%a');
        $hour = $interval->format('%h');
        $min = $interval->format('%i');
        $seconds = $interval->format('%s');

        if ($day >= 1) {
            $expiresAfter = $day . " d";
        } else if ($hour >= 1 && $hour <= 24) {
            $expiresAfter = $hour . " hr";
        } else if ($min >= 1 && $min <= 60) {
            $expiresAfter = $min . " min";
        } else if ($seconds >= 1 && $seconds <= 60) {
            $expiresAfter = $seconds . " sec";
        }
    }
}

$activeSubscriptions = 0;
if ($_SESSION["isAdmin"]) {
    $activeSubscriptions = Subscription::where("Subscription.status", "ACTIVE")->count();
    $query = "SELECT SUM(amount) as TotalAmount, SUM(transactionFee) as TotalFee, currency FROM Payment WHERE status = 'COMPLETED'";
    if (isset($start_date) && isset($end_date)) {
        $query .= " AND Payment.dateAdded >= '{$start_date}' AND Payment.dateAdded <= '{$end_date}'";
    }
    $query .= " GROUP BY currency";
    $data = MysqliDb::getInstance()->rawQueryOne($query);
    $earnings = 0;
    if (isset($data["TotalAmount"])) {
        $earnings = (int)$data["TotalAmount"] - (int)$data["TotalFee"];
        $earnings = "{$earnings} {$data["currency"]}";
    }
} else {
    $activeSubscriptions = Subscription::where("Subscription.status", "ACTIVE")
        ->where("Subscription.userID", $logged_in_user->getID())
        ->count();
}

require_once __DIR__ . "/includes/header.php";
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div>
            <h1>
                <?= __("dashboard") ?>
                <select id="timeIntervalInput"
                        class="form-control pull-right"
                        title="Time Interval"
                        style="margin-top: 5px; width: auto">
                    <option value <?php if (empty($_GET["interval"])) echo 'selected'; ?>><?= __("all_time"); ?></option>
                    <option value="7" <?php if (isset($_GET["interval"]) && $_GET["interval"] == 7) echo 'selected'; ?>>
                        7 <?= __("days"); ?></option>
                    <option value="15" <?php if (isset($_GET["interval"]) && $_GET["interval"] == 15) echo 'selected'; ?>>
                        15 <?= __("days"); ?></option>
                    <option value="30" <?php if (isset($_GET["interval"]) && $_GET["interval"] == 30) echo 'selected'; ?>>
                        30 <?= __("days"); ?></option>
                    <option value="60" <?php if (isset($_GET["interval"]) && $_GET["interval"] == 60) echo 'selected'; ?>>
                        60 <?= __("days"); ?></option>
                    <option value="90" <?php if (isset($_GET["interval"]) && $_GET["interval"] == 90) echo 'selected'; ?>>
                        90 <?= __("days"); ?></option>
                </select>
            </h1>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Announcement Section -->
        <?php if (Setting::get("announcement")) { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-dismissible bg-gray" id="alert-announcement">
                    <button type="button" class="close" onclick="$('#alert-announcement').hide()" aria-hidden="true">
                        &times;
                    </button>
                    <h4><i class="icon fa fa-bullhorn"></i>Announcement</h4>
                    <?= Setting::get("announcement") ?>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <?php } ?>

        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <h3 class="text-bold"><?= __("messages") ?></h3>
            </div>
            <a href="messages.php?status=Pending<?= $queryString ?>">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow-gradient">
                        <div class="inner">
                            <h3 id="pending-count"><?= $pending ?></h3>
                            <p><?= __("pending") ?></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-timer"></i>
                        </div>
                    </div>
                </div>
            </a>
            <!-- ./col -->

            <a href="messages.php?status=Scheduled<?= $queryString ?>">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-blue-gradient">
                        <div class="inner">
                            <h3 id="scheduled-count"><?= $scheduled ?></h3>
                            <p><?= __("scheduled") ?></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-md-calendar"></i>
                        </div>
                    </div>
                </div>
            </a>
            <!-- ./col -->

            <a href="messages.php?status=Queued<?= $queryString ?>">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua-gradient">
                        <div class="inner">
                            <h3 id="queued-count"><?= $queued ?></h3>
                            <p><?= __("queued") ?></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-sync"></i>
                        </div>
                    </div>
                </div>
            </a>
            <!-- ./col -->

            <a href="messages.php?status=Sent<?= $queryString ?>">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green-gradient">
                        <div class="inner">
                            <h3 id="sent-count"><?= $sent ?></h3>
                            <p><?= __("sent") ?></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-done-all"></i>
                        </div>
                    </div>
                </div>
            </a>
            <!-- ./col -->

            <a href="messages.php?status=Delivered<?= $queryString ?>">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green-gradient">
                        <div class="inner">
                            <h3 id="delivered-count"><?= $delivered ?></h3>
                            <p><?= __("delivered") ?></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-md-done-all"></i>
                        </div>
                    </div>
                </div>
            </a>
            <!-- ./col -->

            <a href="messages.php?status=Failed<?= $queryString ?>">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red-gradient">
                        <div class="inner">
                            <h3 id="failed-count"><?= $failed ?></h3>
                            <p><?= __("failed") ?></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-remove-circle"></i>
                        </div>
                    </div>
                </div>
            </a>
            <!-- ./col -->

            <a href="messages.php?status=Canceled<?= $queryString ?>">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box" style="background-color: grey">
                        <div class="inner" style="color: white">
                            <h3 id="canceled-count"><?= $canceled ?></h3>
                            <p ><?= __("canceled") ?></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-hand"></i>
                        </div>
                    </div>
                </div>
            </a>
            <!-- ./col -->

            <a href="messages.php?status=Received<?= $queryString ?>">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-blue-gradient">
                        <div class="inner">
                            <h3 id="received-count"><?= $received ?></h3>
                            <p><?= __("received") ?></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-mail-unread"></i>
                        </div>
                    </div>
                </div>
            </a>
            <!-- ./col -->
        </div>
        <!-- /.row -->

        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <h3 class="text-bold">USSD</h3>
            </div>
            <a href="ussd.php">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow-gradient">
                        <div class="inner">
                            <h3 id="pending-ussd-count"><?= $pendingUssd ?></h3>
                            <p><?= __("pending"); ?></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-timer"></i>
                        </div>
                    </div>
                </div>
            </a>
            <!-- ./col -->

            <a href="ussd.php">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green-gradient">
                        <div class="inner">
                            <h3 id="sent-ussd-count"><?= $sentUssd ?></h3>
                            <p><?= __("sent"); ?></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-done-all"></i>
                        </div>
                    </div>
                </div>
            </a>
            <!-- ./col -->
        </div>
        <!-- /.row -->

        <?php
        if ($activeSubscriptions > 0) {
            $attributes = 'href="subscriptions.php"';
        } else {
            if (Setting::get("paypal_enabled") && $activePlansCount > 0) {
                $attributes = 'href="subscribe.php"';
            } else {
                $attributes = sprintf('href="%s" target="_blank"', __("get_credits_url"));
            }
        }
        ?>

        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <h3 class="text-bold"><?= __("subscription") ?></h3>
            </div>
            <a <?=$attributes?>>
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-purple-gradient">
                        <div class="inner">
                            <h3><?= $credits ?></h3>
                            <p><?= __("available") ?></p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-credit-card"></i>
                        </div>
                    </div>
                </div>
            </a>
            <!-- ./col -->

            <?php if (isset($expiresAfter)) { ?>
                <a <?=$attributes?>>
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-purple-gradient">
                            <div class="inner">
                                <h3><?= $expiresAfter ?></h3>
                                <p><?= __("expires_after") ?></p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-ios-time"></i>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- ./col -->
            <?php } ?>
        </div>
        <!-- /.row -->

        <?php if ($_SESSION["isAdmin"]) { ?>
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-bold"><?= __("admin") ?></h3>
                </div>
                <a href="subscriptions.php">
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-maroon-gradient">
                            <div class="inner">
                                <h3><?= $activeSubscriptions ?></h3>
                                <p><?= __("active_subscriptions") ?></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-newspaper-o"></i>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- ./col -->

                <a href="subscriptions.php">
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-black-gradient">
                            <div class="inner">
                                <h3><?= $earnings ?></h3>
                                <p><?= __("earnings") ?></p>
                            </div>
                            <div class="icon" style="color: white">
                                <i class="fa fa-money"></i>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- ./col -->
            </div>
            <!-- /.row -->
        <?php } ?>

        <?php
        $showTutorial = isset($_SESSION["showTutorial"]);
        if ($showTutorial) {
            unset($_SESSION["showTutorial"]);
            require_once __DIR__ . "/includes/add-device.php";
        }
        ?>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once __DIR__ . "/includes/footer.php"; ?>

<?php if ($showTutorial) { ?>
    <script type="text/javascript">
        $(function () {
            $('#modal-add-device').modal({backdrop: 'static', keyboard: false});
        });
    </script>
<?php } ?>
<script>
    $(function () {
        $('#timeIntervalInput').change(function (event) {
            event.preventDefault();
            window.location.href = location.protocol + '//' + location.host + location.pathname + `?interval=${$(this).val()}`
        });
        <?php if (Setting::get("realtime_dashboard_enabled")) { ?>
        (function getStatus() {
            ajaxRequest(`ajax/get-status.php${window.location.search}`)
                .then(function (result) {
                    $("#scheduled-count").html(result.messages.scheduled);
                    $("#pending-count").html(result.messages.pending);
                    $("#queued-count").html(result.messages.queued);
                    $("#sent-count").html(result.messages.sent);
                    $("#delivered-count").html(result.messages.delivered);
                    $("#failed-count").html(result.messages.failed);
                    $("#canceled-count").html(result.messages.canceled);
                    $("#received-count").html(result.messages.received);
                    $("#pending-ussd-count").html(result.ussd.pending);
                    $("#sent-ussd-count").html(result.ussd.sent);
                }).finally(function () {
                    setTimeout(function () {
                        getStatus();
                    }, 5000);
                });
        })();
        <?php } ?>
    })
</script>
</body>
</html>
