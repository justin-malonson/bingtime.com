<?php
/**
 * @var User $logged_in_user
 * @var Device $currentDevice
 */

require_once __DIR__ . "/includes/login.php";

$title = __("application_title") . " | " . __("devices");
$users = User::read_all();

require_once __DIR__ . "/includes/header.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= __("devices"); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="pull-left select-user">
                            <?php if ($_SESSION["isAdmin"]) { ?>
                                <select title="<?= __("user"); ?>" class="form-control select2" id="user">
                                    <?php
                                    foreach ($users as $user) {
                                        createOption(strval($user), $user->getID(), $user->getID() == $_SESSION["userID"]);
                                    }
                                    ?>
                                </select>
                            <?php } else { ?>
                                <h3 class="box-title"><?= __("devices"); ?></h3>
                            <?php } ?>
                        </div>
                        <div class="pull-right">
                            <button type="button" id="remove-selected" class="btn btn-danger" disabled><i
                                        class="icon fa fa-remove"></i><span
                                        class="hidden-xs hidden-sm">&nbsp;<?= __("remove"); ?></span></button>
                            <button type="button" id="add-device" style="margin-right: 4px"
                                    class="btn btn-primary" data-toggle="modal"
                                    data-target="#modal-add-device">
                                <i class="icon fa fa-plus"></i><span
                                        class="hidden-xs hidden-sm">&nbsp;<?= __("add_device"); ?></span></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="devices" class="table table-bordered table-striped ">
                            <thead>
                            <tr>
                                <th>
                                    <label>
                                        <input type="checkbox" id="check-all">
                                    </label>
                                </th>
                                <th><?= __("name"); ?></th>
                                <th><?= __("device_model"); ?></th>
                                <th><?= __("android_version"); ?></th>
                                <th><?= __("app_version"); ?></th>
                                <th><?= __("total_messages"); ?></th>
                                <th><?= __("device_status"); ?></th>
                                <?php if ($_SESSION["isAdmin"]) { ?>
                                <th><?= __("shared_with"); ?></th>
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

        <?php require_once __DIR__ . "/includes/add-device.php"; ?>

        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><?= __("edit_device") ?></h4>
                    </div>
                    <form id="edit-device">
                        <div class="modal-body">
                            <input type="hidden" name="deviceID" id="deviceIDEditInput" class="form-control"
                                   required="required">
                            <div class="form-group">
                                <label for="deviceNameEditInput"><?= __("device_name"); ?>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-mobile"></i>
                                    </div>
                                    <input type="text" name="name" class="form-control"
                                           id="deviceNameEditInput">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i
                                        class="fa fa-remove"></i>&nbsp;<?= __("close") ?>
                            </button>
                            <button type="submit" id="editDeviceButton" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;<?= __("save_changes") ?>
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <?php if ($_SESSION["isAdmin"]) { ?>
        <div class="modal fade" id="modal-share-devices">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><?= __("share_device") ?></h4>
                    </div>
                    <form id="share-device">
                        <div class="modal-body">
                            <input type="hidden" name="deviceID" id="deviceIDShareInput" class="form-control"
                                   required="required">
                            <div class="form-group">
                                <label for="shareToAllInput">
                                    <?= __("share_with"); ?>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-share"></i>
                                    </div>
                                    <select name="shareToAll" class="form-control select2" id="shareToAllInput" style="width: 100%;">
                                        <option value="1"><?= __("all_users"); ?></option>
                                        <option value="2"><?= __("demo_users"); ?></option>
                                        <option value="0"><?= __("selected_users"); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="shareWithFormGroup" hidden>
                                <label for="shareWithInput">
                                    <?= __("users"); ?>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <select name="shareWith[]" class="form-control select2" id="shareWithInput" style="width: 100%;" multiple>
                                        <?php
                                        foreach ($users as $user) {
                                            if ($user->getID() == $_SESSION["userID"]) {
                                                continue;
                                            }
                                            createOption(strval($user), $user->getID(), false);
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <label for="useOwnerSettingsInput">
                                <input type="checkbox" name="useOwnerSettings" value="1" id="useOwnerSettings">
                                <?= __("use_owner_settings"); ?>
                            </label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i
                                        class="fa fa-remove"></i>&nbsp;<?= __("close") ?>
                            </button>
                            <button type="submit" id="shareDeviceButton" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;<?= __("save_changes") ?>
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
<script src="components/he/he.js"></script>
<script type="text/javascript">
    const removeSelected = $('#remove-selected');
    let shareWithInput = $('#shareWithInput');
    let shareToAllInput = $('#shareToAllInput');

    <?php if ($_SESSION["isAdmin"]) { ?>
    function shareDevice(sharedWith, id, sharedToAll, useOwnerSettings) {
        $('#deviceIDShareInput').val(id);
        shareToAllInput.val(sharedToAll).change();
        if (sharedToAll) {
            shareWithInput.prop('disabled', true);
            $('#shareWithFormGroup').prop('hidden', true);
        } else {
            shareWithInput.prop('disabled', false);
            $('#shareWithFormGroup').prop('hidden', false);
        }
        shareWithInput.val(sharedWith).change();
        $('#useOwnerSettings').prop('checked', useOwnerSettings);
        $('#modal-share-devices').modal('show');
    }
    <?php } ?>

    function editDevice(name, id) {
        $('#deviceIDEditInput').val(id);
        if (name !== null) {
            $('#deviceNameEditInput').val(name);
        } else {
            $('#deviceNameEditInput').val("");
        }
        $('#modal-default').modal('show');
    }

    function toggleRemove() {
        if ($('input.remove-devices:checked').length > 0) {
            removeSelected.prop('disabled', false);
        } else {
            removeSelected.prop('disabled', true);
        }
    }

    $(function () {
        const checkAllInput = $('#check-all');
        const editDeviceButton = $('#editDeviceButton');
        const devicesTable = $('#devices');

        shareToAllInput.change(function () {
            if ($(this).val() > 0) {
                $('#shareWithFormGroup').prop('hidden', true);
                shareWithInput.prop('disabled', true);
            } else {
                $('#shareWithFormGroup').prop('hidden', false);
                shareWithInput.prop('disabled', false);
            }
        });

        checkAllInput.click(function () {
            if ($(this).is(':checked')) {
                $('.remove-devices').prop('checked', true);
            } else {
                $('.remove-devices').prop('checked', false);
            }
            toggleRemove();
        });

        const table = devicesTable.DataTable({
            <?php if (isset($dataTablesLanguage)) { ?>
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/<?=$dataTablesLanguage?>.json"
            },
            <?php } ?>
            autoWidth: false,
            serverSide: true,
            pagingType: "simple",
            responsive: true,
            columnDefs: [
                {
                    orderable: false,
                    targets: 0
                },
                {
                    orderable: false,
                    targets: 5
                },
                {
                    orderable: false,
                    targets: 6
                },
                <?php if ($_SESSION["isAdmin"]) { ?>
                {
                    orderable: false,
                    targets: 7
                }
                <?php } ?>
            ]
        });

        <?php if ($_SESSION["isAdmin"]) { ?>
        let selectUser = $("#user");
        selectUser.change(function () {
            if (table.ajax.url()) {
                table.ajax.url("ajax/get-devices.php?user=" + $(this).val()).load();
            } else {
                table.ajax.url("ajax/get-devices.php?user=" + $(this).val())
            }
            $("#qr-code").attr("src", `qr-code.php?user=${$(this).val()}`);
            reset();
        });

        selectUser.change();
        <?php } else { ?>
        table.ajax.url("ajax/get-devices.php")
        <?php } ?>

        function reset() {
            $('.remove-devices').prop('checked', false);
            checkAllInput.prop('checked', false);
            removeSelected.prop('disabled', true);
        }

        devicesTable.on('page.dt length.dt', reset);

        function reloadDevices() {
            table.ajax.reload();
            reset();
        }

        <?php if ($_SESSION["isAdmin"]) { ?>
        const shareDeviceButton = $('#shareDeviceButton');
        $('#share-device').submit(function (e) {
            e.preventDefault();
            let postData = $('#share-device').serialize();
            let url = "ajax/share-device.php";
            shareDeviceButton.prop('disabled', true);
            const options = {positionClass: "toast-top-center", closeButton: true};
            ajaxRequest(url, postData).then(result => {
                toastr.success(result, null, options);
                reloadDevices();
                $('#modal-share-devices').modal('hide');
            }).catch(reason => {
                toastr.error(reason, null, options);
            }).finally(() => {
                shareDeviceButton.prop('disabled', false);
            });
        });
        <?php } ?>

        $('#edit-device').submit(function (e) {
            e.preventDefault();
            let postData = $('#edit-device').serialize();
            let url = "ajax/edit-device.php";
            editDeviceButton.prop('disabled', true);
            const options = {positionClass: "toast-top-center", closeButton: true};
            ajaxRequest(url, postData).then(result => {
                toastr.success(result.message, null, options);
                reloadDevices();
                let updatedDeviceId = $('#deviceIDEditInput').val();
                <?php if (isset($_COOKIE["DEVICE_ID"])) { ?>
                if (updatedDeviceId == <?=$_COOKIE["DEVICE_ID"]?>) {
                    Android.changeDeviceName($('#deviceNameEditInput').val());
                }
                <?php } ?>
                let optionElement = $(`#deviceInput option[value="${updatedDeviceId}"]`);
                if (optionElement) {
                    let name = he.encode(result.data.name);
                    optionElement.html(name);
                    $('#deviceInput').select2();
                }
                $('#modal-default').modal('hide');
            }).catch(reason => {
                toastr.error(reason, null, options);
            }).finally(() => {
                editDeviceButton.prop('disabled', false);
            });
        });

        $("#remove-selected").click(function () {
            let postData = table.$('input').serialize();
            if (postData) {
                let result = confirm("<?=__("remove_devices_confirmation");?>");
                if (result) {
                    let url = "ajax/remove-devices.php";
                    $(this).prop('disabled', true);
                    ajaxRequest(url, postData).then(result => {
                        toastr.success(result);
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }).catch(reason => {
                        toastr.error("<?=__("error_unable_to_remove_devices");?>" + ` ${reason}`);
                        $(this).prop('disabled', false);
                    });
                }
            }
        });
    });
</script>
</body>
</html>