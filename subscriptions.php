<?php
require_once __DIR__ . "/includes/login.php";

$title = __("application_title") . " | " . __("subscriptions");

if ($_SESSION["isAdmin"]) {
    $users = User::where("isAdmin", "0")->read_all();
    $plans = Plan::read_all();
}
require_once __DIR__ . "/includes/header.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= __("subscriptions"); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("subscriptions"); ?></h3>
                        <?php if ($_SESSION["isAdmin"]) { ?>
                            <button type="button" id="button-create-subscription" class="btn btn-primary pull-right"
                                    data-toggle="modal" data-target="#modal-default"><i
                                        class="icon fa fa-plus"></i>&nbsp;<?= __("create_subscription"); ?></button>
                        <?php } ?>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="subscriptions" class="table table-bordered table-striped ">
                            <thead>
                            <tr>
                                <?php if ($_SESSION["isAdmin"]) { ?>
                                    <th><?= __("user"); ?></th>
                                <?php } ?>
                                <th><?= __("id"); ?></th>
                                <th><?= __("plan"); ?></th>
                                <th><?= __("subscribed_date"); ?></th>
                                <th><?= __("renew_date"); ?></th>
                                <th><?= __("cycles_competed"); ?></th>
                                <th><?= __("renews_until"); ?></th>
                                <th><?= __("payment_method"); ?></th>
                                <th><?= __("status"); ?></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("payments"); ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="payments" class="table table-bordered table-striped ">
                            <thead>
                            <tr>
                                <th><?= __("payment_id"); ?></th>
                                <th><?= __("status"); ?></th>
                                <th><?= __("amount"); ?></th>
                                <th><?= __("transaction_fee"); ?></th>
                                <th><?= __("subscription_id"); ?></th>
                                <th><?= __("payment_method"); ?></th>
                                <th><?= __("date_added"); ?></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
        <!-- /.row -->

        <?php if ($_SESSION["isAdmin"]) { ?>
            <div class="modal fade" id="modal-default">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><?= __("create_subscription") ?></h4>
                        </div>
                        <form id="createSubscriptionForm">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="userIDInput"><?= __("user"); ?></label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <select name="userID" class="form-control select2" id="userIDInput"
                                                style="width: 100%" required>
                                            <?php
                                            foreach ($users as $user) {
                                                createOption(strval($user), $user->getID(), false);
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="planIDInput"><?= __("plan"); ?></label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-table"></i>
                                        </div>
                                        <select name="planID" class="form-control select2" id="planIDInput"
                                                style="width: 100%" required>
                                            <?php
                                            foreach ($plans as $plan) {
                                                createOption($plan->getName(), $plan->getID(), false);
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i
                                            class="fa fa-remove"></i>&nbsp;<?= __("close") ?>
                                </button>
                                <button type="submit" id="createSubscriptionButton" class="btn btn-primary"><i
                                            class="fa fa-save"></i>&nbsp;<?= __("save_changes") ?>
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        <?php } ?>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once __DIR__ . "/includes/footer.php"; ?>
<?php require_once __DIR__ . "/includes/common-js.php"; ?>

<script type="text/javascript">
    $(function () {
        const createSubscriptionForm = $('#createSubscriptionForm');
        const createSubscriptionButton = $('#createSubscriptionButton');

        const subscriptionsTable = $('#subscriptions').DataTable({
            <?php if (isset($dataTablesLanguage)) { ?>
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/<?=$dataTablesLanguage?>.json"
            },
            <?php } ?>
            autoWidth: false,
            pagingType: "simple",
            responsive: true,
            order: [[ 2, "desc" ]],
            ajax: "ajax/get-subscriptions.php",
            initComplete: initSubscriptions
        });

        function initSubscriptions() {
            $("#subscriptions").off("click", ".cancel-subscription").on("click", ".cancel-subscription", function (event) {
                event.preventDefault();
                let result = confirm("<?=__("cancel_subscription_confirmation");?>");
                if (result) {
                    let subscriptionId = $(this).data("id");
                    let postData = { subscriptionID: subscriptionId };
                    let url = "ajax/cancel-subscription.php";
                    $(this).prop('disabled', true);
                    ajaxRequest(url, postData).then(result => {
                        toastr.success(result);
                        subscriptionsTable.ajax.reload(initSubscriptions);
                    }).catch(reason => {
                        toastr.error(reason);
                    }).finally(() => {
                        $(this).prop('disabled', false);
                    });
                }
            });
        }

        const paymentsTable = $('#payments').DataTable({
            <?php if (isset($dataTablesLanguage)) { ?>
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/<?=$dataTablesLanguage?>.json"
            },
            <?php } ?>
            autoWidth: false,
            pagingType: "simple",
            responsive: true,
            order: [[ 6, "desc" ]],
            ajax: "ajax/get-payments.php",
            initComplete: initPayments
        });

        function initPayments() {
            $("#payments").off("click", ".refund-payment").on("click", ".refund-payment", function (event) {
                event.preventDefault();
                let result = confirm("<?=__("refund_payment_confirmation");?>");
                if (result) {
                    let paymentId = $(this).data("id");
                    let postData = { paymentID: paymentId };
                    let url = "ajax/refund-payment.php";
                    $(this).prop('disabled', true);
                    ajaxRequest(url, postData).then(result => {
                        toastr.success(result);
                        paymentsTable.ajax.reload(initPayments);
                        subscriptionsTable.ajax.reload(initSubscriptions);
                    }).catch(reason => {
                        toastr.error(reason);
                    }).finally(() => {
                        $(this).prop('disabled', false);
                    });
                }
            });
        }

        createSubscriptionForm.submit(function (event) {
            event.preventDefault();
            let postData = $(this).serialize();
            let url = "ajax/create-subscription.php";
            const options = {positionClass: "toast-top-center", closeButton: true};
            createSubscriptionButton.prop('disabled', true);
            ajaxRequest(url, postData).then(result => {
                toastr.success(result, null, options);
                subscriptionsTable.ajax.reload(initSubscriptions);
                $('#modal-default').modal('hide');
            }).catch(reason => {
                toastr.error(reason, null, options);
            }).finally(() => {
                createSubscriptionButton.prop('disabled', false);
            });
        });
    });
</script>
</body>
</html>