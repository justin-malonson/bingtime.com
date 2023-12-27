<?php
/**
 * @var User $logged_in_user
 */

require_once __DIR__ . "/includes/login.php";

$title = __("application_title") . " | " . __("ussd");

require_once __DIR__ . "/includes/header.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= __("ussd"); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("send_ussd_request"); ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" id="form-send-ussd" method="post">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="requestInput">
                                    <?= __("ussd_request"); ?>
                                    <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="bottom"
                                       title="<?= __('tooltip_ussd'); ?>"></i>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" name="request" class="form-control" maxlength="182"
                                           id="requestInput"
                                           placeholder="<?= __("ussd_request"); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="deviceInput"><?= __("device"); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-android"></i>
                                    </div>
                                    <select class="form-control select2" name="device" id="deviceInput" style="width: 100%;"
                                            required="required">
                                        <?php
                                        $selectedDevice = $logged_in_user->getPrimaryDeviceID();
                                        $logged_in_user->generateDevicesList($selectedDevice, 8, true);
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="simInput"><?= __("sim"); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-android"></i>
                                    </div>
                                    <select class="form-control select2" name="sim" id="simInput" style="width: 100%;">
                                        <option value=""><?= __("default"); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" id="btn-send-ussd" class="btn btn-primary"><i
                                    class="fa fa-send"></i>&nbsp;<?= __("send"); ?>
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->

                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="pull-left select-user">
                            <?php if ($_SESSION["isAdmin"]) { ?>
                                <select title="<?= __("user"); ?>" class="form-control select2" id="user">
                                    <option value='0'><?= __("all_users"); ?></option>
                                    <?php
                                    $users = User::read_all();
                                    foreach ($users as $user) {
                                        createOption(strval($user), $user->getID(), $user->getID() == $_SESSION["userID"]);
                                    }
                                    ?>
                                </select>
                            <?php } else { ?>
                                <h3 class="box-title"><?= __("ussd_requests"); ?></h3>
                            <?php } ?>
                        </div>
                        <div class="pull-right">
                            <button type="button" id="resend-selected" class="btn btn-primary" disabled><i
                                        class="icon fa fa-send"></i><span
                                        class="hidden-xs hidden-sm">&nbsp;<?= __("resend"); ?></span></button>
                            <button type="button" id="remove-selected" class="btn btn-danger" disabled><i
                                        class="icon fa fa-remove"></i><span
                                        class="hidden-xs hidden-sm">&nbsp;<?= __("remove"); ?></span></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="dt-ussd-requests" class="table table-bordered table-striped ">
                            <thead>
                            <tr>
                                <th>
                                    <label>
                                        <input type="checkbox" id="check-all">
                                    </label>
                                </th>
                                <th><?= __("request"); ?></th>
                                <th><?= __("response"); ?></th>
                                <th><?= __("sent_at"); ?></th>
                                <th><?= __("response_at"); ?></th>
                                <th><?= __("device"); ?></th>
                                <th><?= __("sim"); ?></th>
                                <?php if ($_SESSION["isAdmin"]) { ?>
                                    <th><?= __("user"); ?></th>
                                <?php } ?>
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
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require_once __DIR__ . "/includes/footer.php"; ?>
<?php require_once __DIR__ . '/includes/common-js.php'; ?>
<?php require_once __DIR__ . '/includes/user-sims.php'; ?>

<script type="text/javascript">
    const removeSelected = $('#remove-selected');
    const resendSelected = $('#resend-selected');

    function toggleOptions() {
        if ($('input.remove-requests:checked').length > 0) {
            removeSelected.prop('disabled', false);
            resendSelected.prop('disabled', false);
        } else {
            removeSelected.prop('disabled', true);
            resendSelected.prop('disabled', true);
        }
    }

    $(function () {
        const checkAllInput = $('#check-all');
        const sendUssdRequestButton = $('#btn-send-ussd');
        const ussdRequestsTable = $('#dt-ussd-requests');

        checkAllInput.click(function () {
            if ($(this).is(':checked')) {
                $('.remove-requests').prop('checked', true);
            } else {
                $('.remove-requests').prop('checked', false);
            }
            toggleOptions();
        });

        $('#form-send-ussd').submit(function (event) {
            event.preventDefault();
            let postData = $('#form-send-ussd').serialize();
            let url = "ajax/send-ussd-request.php";
            sendUssdRequestButton.prop('disabled', true);
            ajaxRequest(url, postData).then(result => {
                toastr.success(result);
                $('form#form-send-ussd').trigger("reset");
                $('#deviceInput').change();
                $('#simInput').change();
                reloadRequests();
            }).catch(reason => {
                toastr.error(reason);
            }).finally(() => {
                sendUssdRequestButton.prop('disabled', false);
            });
        });

        const table = ussdRequestsTable.DataTable({
            <?php if (isset($dataTablesLanguage)) { ?>
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/<?=$dataTablesLanguage?>.json"
            },
            <?php } ?>
            autoWidth: false,
            serverSide: true,
            pagingType: "simple",
            responsive: true,
            order: [[ 3, "desc" ]],
            columnDefs: [
                {
                    orderable: false,
                    targets: 0
                },
                {
                    orderable: false,
                    targets: 2
                }<?php if ($_SESSION["isAdmin"]) { ?>,
                {
                    orderable: false,
                    visible: false,
                    targets: 7
                },
                <?php } ?>
            ],
            ajax: "ajax/get-ussd-requests.php"
        });

        function reset() {
            $('.remove-requests').prop('checked', false);
            checkAllInput.prop('checked', false);
            removeSelected.prop('disabled', true);
            resendSelected.prop('disabled', true);
        }

        ussdRequestsTable.on('page.dt length.dt', reset);

        <?php if ($_SESSION["isAdmin"]) { ?>

        $("#user").change(function () {
            let userId = $(this).val();
            if (userId !== "0") {
                table.column(7).visible(false);
            } else {
                table.column(7).visible(true);
            }

            table.ajax.url("ajax/get-ussd-requests.php?user=" + userId).load();
        });
        <?php } ?>

        function reloadRequests() {
            table.ajax.reload();
            reset();
        }

        resendSelected.click(function () {
            let postData = table.$('input').serialize();
            if (postData) {
                let result = confirm("<?=__("resend_requests_confirmation");?>");
                if (result) {
                    let url = "ajax/resend-ussd-requests.php";
                    $(this).prop('disabled', true);
                    ajaxRequest(url, postData).then(result => {
                        toastr.success(result);
                        reloadRequests();
                    }).catch(reason => {
                        toastr.error(reason);
                        $(this).prop('disabled', false);
                    });
                }
            }
        });

        removeSelected.click(function () {
            let postData = table.$('input').serialize();
            if (postData) {
                let result = confirm("<?=__("remove_requests_confirmation");?>");
                if (result) {
                    let url = "ajax/remove-ussd-requests.php";
                    $(this).prop('disabled', true);
                    ajaxRequest(url, postData).then(result => {
                        toastr.success(result);
                        reloadRequests();
                    }).catch(reason => {
                        toastr.error(reason);
                        $(this).prop('disabled', false);
                    });
                }
            }
        });
    });
</script>
</body>
</html>
