<?php
require_once __DIR__ . "/includes/login.php";

if (!$_SESSION["isAdmin"]) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}

$title = __("application_title") . " | " . __("manage_users");

require_once __DIR__ . "/includes/header.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= __("manage_users"); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("add_user_form_title"); ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" id="create-user" method="post">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="nameInput"><?= __("name"); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="glyphicon glyphicon-user"></i>
                                    </div>
                                    <input type="text" name="name" class="form-control" id="nameInput"
                                           placeholder="<?= __("name"); ?>" required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="emailInput"><?= __("email"); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="email" name="email" class="form-control" id="emailInput"
                                           placeholder="<?= __("email"); ?>" required="required">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="confirmEmailInput"><?= __("confirm_email"); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="email" name="confirmEmail" class="form-control" id="confirmEmailInput"
                                           placeholder="<?= __("confirm_email"); ?>" required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="devicesLimitInput"><input type="checkbox" id="toggleDevicesLimitInput"
                                                                      onchange="disableInput('#toggleDevicesLimitInput', '#devicesLimitInput')">
                                    <?= __("max_devices"); ?>
                                    <i class="fa fa-info-circle" data-toggle="tooltip" title="<?= __('tooltip_only_shared_devices'); ?>"></i>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-android"></i>
                                    </div>
                                    <input type="number" name="devicesLimit" class="form-control" min="0"
                                           id="devicesLimitInput"
                                           placeholder="<?= __("max_devices"); ?>"
                                           required="required" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="contactsLimitInput"><input type="checkbox" id="toggleContactsLimitInput"
                                                                       onchange="disableInput('#toggleContactsLimitInput', '#contactsLimitInput')">&nbsp;<?= __("max_contacts"); ?>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-address-book"></i>
                                    </div>
                                    <input type="number" name="contactsLimit" class="form-control" min="0"
                                           id="contactsLimitInput"
                                           placeholder="<?= __("max_contacts"); ?>"
                                           required="required" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="creditsInput"><input type="checkbox" id="toggleCreditsInput"
                                                                 onchange="disableInput('#toggleCreditsInput', '#creditsInput')">&nbsp;<?= __("credits"); ?>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-credit-card"></i>
                                    </div>
                                    <input type="number" name="credits" class="form-control" id="creditsInput" min="0"
                                           placeholder="<?= __("credits"); ?>"
                                           required="required" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="expiryDateInput"><input type="checkbox" id="toggleExpiryDateInput"
                                                                    onchange="disableInput('#toggleExpiryDateInput', '#expiryDateInput')">&nbsp;<?= __("expiry_date"); ?>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <input type="datetime-local" name="expiryDate" class="form-control datetime-local"
                                           id="expiryDateInput" required="required" disabled>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" id="createUserButton" name="createUser" class="btn btn-primary"><i
                                        class="fa fa-user-plus"></i>&nbsp;<?= __("create_user"); ?></button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("manage_users"); ?></h3>
                        <button type="button" id="remove-selected" class="btn btn-danger pull-right" disabled><i
                                    class="icon fa fa-remove"></i>&nbsp;<?= __("remove"); ?></button>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="users" class="table table-bordered table-striped ">
                            <thead>
                            <tr>
                                <th>
                                    <label>
                                        <input type="checkbox" id="check-all">
                                    </label>
                                </th>
                                <th><?= __("name"); ?></th>
                                <th><?= __("email"); ?></th>
                                <th><?= __("total_messages"); ?></th>
                                <th><?= __("connected_devices"); ?></th>
                                <th><?= __("max_devices"); ?></th>
                                <th><?= __("max_contacts"); ?></th>
                                <th><?= __("credits"); ?></th>
                                <th><?= __("expiry_date"); ?></th>
                                <th><?= __("delay"); ?></th>
                                <th><?= __("date_added"); ?></th>
                                <th><?= __("last_login"); ?></th>
                                <th><?= __("last_login_ip"); ?></th>
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

        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><?= __("edit_user") ?></h4>
                    </div>
                    <form id="edit-user">
                        <div class="modal-body">
                            <input type="hidden" name="userID" id="userIDEditInput" class="form-control"
                                   required="required">
                            <div class="form-group">
                                <label for="devicesLimitEditInput"><input type="checkbox"
                                                                          id="toggleDevicesLimitEditInput"
                                                                          onchange="disableInput('#toggleDevicesLimitEditInput', '#devicesLimitEditInput')">
                                    <?= __("max_devices"); ?>
                                    <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="<?= __('tooltip_only_shared_devices'); ?>"></i>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-android"></i>
                                    </div>
                                    <input type="number" name="devicesLimit" class="form-control"
                                           id="devicesLimitEditInput" min="0" required="required" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="contactsLimitEditInput"><input type="checkbox"
                                                                           id="toggleContactsLimitEditInput"
                                                                           onchange="disableInput('#toggleContactsLimitEditInput', '#contactsLimitEditInput')">&nbsp;<?= __("max_contacts"); ?>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-address-book"></i>
                                    </div>
                                    <input type="number" name="contactsLimit" class="form-control"
                                           id="contactsLimitEditInput" min="0" required="required" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="creditsEditInput"><input type="checkbox" id="toggleCreditsEditInput"
                                                                     onchange="disableInput('#toggleCreditsEditInput', '#creditsEditInput')">&nbsp;<?= __("credits"); ?>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-credit-card"></i>
                                    </div>
                                    <input type="number" name="credits" class="form-control" id="creditsEditInput"
                                           min="0"
                                           required="required" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="expiryDateEditInput"><input type="checkbox" id="toggleExpiryDateEditInput"
                                                                        onchange="disableInput('#toggleExpiryDateEditInput', '#expiryDateEditInput')">&nbsp;<?= __("expiry_date"); ?>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <input type="datetime-local" name="expiryDate" class="form-control datetime-local"
                                           id="expiryDateEditInput" required="required" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i
                                        class="fa fa-remove"></i>&nbsp;<?= __("close") ?>
                            </button>
                            <button type="submit" id="editUserButton" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;<?= __("save_changes") ?>
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once __DIR__ . "/includes/footer.php"; ?>
<?php require_once __DIR__ . "/includes/common-js.php"; ?>
<script src="components/flatpickr/dist/flatpickr.min.js"></script>

<script type="text/javascript">
    const removeSelected = $('#remove-selected');

    function toggleRemove() {
        if ($('input.remove-users:checked').length > 0) {
            removeSelected.prop('disabled', false);
        } else {
            removeSelected.prop('disabled', true);
        }
    }

    function editUser(expiryDate, credits, devicesLimit, contactsLimit, id) {
        let toggleCredits = $('#toggleCreditsEditInput');
        let toggleExpiryDate = $('#toggleExpiryDateEditInput');
        let toggleDevicesLimit = $('#toggleDevicesLimitEditInput');
        let toggleContactsLimit = $('#toggleContactsLimitEditInput');
        $('#userIDEditInput').val(id);
        if (credits !== null) {
            toggleCredits.prop("checked", true);
            $('#creditsEditInput').val(credits);
        } else {
            toggleCredits.prop("checked", false);
            $('#creditsEditInput').val("");
        }
        if (expiryDate !== null) {
            toggleExpiryDate.prop("checked", true);
            $('#expiryDateEditInput').val(expiryDate);
        } else {
            toggleExpiryDate.prop("checked", false);
            $('#expiryDateEditInput').val("");
        }
        if (devicesLimit !== null) {
            toggleDevicesLimit.prop("checked", true);
            $('#devicesLimitEditInput').val(devicesLimit);
        } else {
            toggleDevicesLimit.prop("checked", false);
            $('#devicesLimitEditInput').val("");
        }
        if (contactsLimit !== null) {
            toggleContactsLimit.prop("checked", true);
            $('#contactsLimitEditInput').val(contactsLimit);
        } else {
            toggleContactsLimit.prop("checked", false);
            $('#contactsLimitEditInput').val("");
        }
        toggleExpiryDate.trigger('change');
        toggleDevicesLimit.trigger('change');
        toggleContactsLimit.trigger('change');
        toggleCredits.trigger('change');
        $('#modal-default').modal('show');
    }

    $(function () {
        const checkAllInput = $('#check-all');
        const createUserForm = $('#create-user');
        const createUserButton = $('#createUserButton');
        const editUserButton = $('#editUserButton');
        const usersTable = $('#users')

        <?php if(!isset($_COOKIE["DEVICE_ID"])) { ?>
        $(".datetime-local").flatpickr({
            enableTime: true,
            allowInput: true,
            disableMobile: true
        });
        <?php } ?>

        createUserForm.validate({
            rules: {
                email: "required",
                confirmEmail: {
                    equalTo: "#emailInput"
                }
            },
            submitHandler: function (form) {
                let postData = createUserForm.serialize();
                let url = "ajax/create-user.php";
                createUserButton.prop('disabled', true);
                ajaxRequest(url, postData).then(result => {
                    toastr.success(result);
                    reloadUsers();
                    form.reset();
                    $('#creditsInput').prop('disabled', true);
                    $('#contactsLimitInput').prop('disabled', true);
                    $('#devicesLimitInput').prop('disabled', true);
                    $('#expiryDateInput').prop('disabled', true);
                }).catch(reason => {
                    toastr.error(reason);
                }).finally(() => {
                    createUserButton.prop('disabled', false);
                });
                return false;
            }
        });

        checkAllInput.click(function () {
            if ($(this).is(':checked')) {
                $('.remove-users').prop('checked', true);
            } else {
                $('.remove-users').prop('checked', false);
            }
            toggleRemove();
        });

        const table = usersTable.DataTable({
            <?php if (isset($dataTablesLanguage)) { ?>
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/<?=$dataTablesLanguage?>.json"
            },
            <?php } ?>
            autoWidth: false,
            pagingType: "simple",
            responsive: true,
            order: [[ 10, "desc" ]],
            columnDefs: [
                {
                    orderable: false,
                    targets: 0
                }
            ],
            ajax: "ajax/get-users.php"
        });

        function reset() {
            $('.remove-users').prop('checked', false);
            checkAllInput.prop('checked', false);
            removeSelected.prop('disabled', true);
        }

        usersTable.on('page.dt length.dt', reset);

        function reloadUsers() {
            table.ajax.reload();
            reset();
        }

        $('#edit-user').submit(function (e) {
            e.preventDefault();
            let postData = $('#edit-user').serialize();
            let url = "ajax/edit-user.php";
            editUserButton.prop('disabled', true);
            const options = {positionClass: "toast-top-center", closeButton: true};
            ajaxRequest(url, postData).then(result => {
                toastr.success(result, null, options);
                reloadUsers();
            }).catch(reason => {
                toastr.error(reason, null, options);
            }).finally(() => {
                editUserButton.prop('disabled', false);
            });
        });

        $("#remove-selected").click(function () {
            let postData = table.$('input').serialize();
            if (postData) {
                let result = confirm("<?=__("remove_users_confirmation");?>");
                if (result) {
                    let url = "ajax/remove-users.php";
                    $(this).prop('disabled', true);
                    ajaxRequest(url, postData).then(result => {
                        toastr.success(result);
                        reloadUsers();
                    }, reason => {
                        toastr.error("<?=__("error_unable_to_remove_users");?>" + ` ${reason}`);
                        $(this).prop('disabled', false);
                    });
                }
            }
        });
    });
</script>
</body>
</html>