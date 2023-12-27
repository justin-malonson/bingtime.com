<?php
require_once __DIR__ . "/includes/login.php";

if (!$_SESSION["isAdmin"]) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}

$title = __("application_title") . " | " . __("plans");

require_once __DIR__ . "/includes/header.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= __("plans"); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("add_plan_form_title"); ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" id="createPlanForm" method="post">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="nameInput"><?= __("name"); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-address-card"></i>
                                    </div>
                                    <input type="text" name="name" maxlength="127" class="form-control" id="nameInput"
                                           placeholder="<?= __("name"); ?>" required="required">
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
                                           required="required"
                                           placeholder="<?= __("max_devices"); ?>" disabled>
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
                                    <input type="number" name="credits" class="form-control" id="creditsInput" min="1"
                                           required="required" placeholder="<?= __("credits"); ?>" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="priceInput"><?= __("price"); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-money"></i>
                                    </div>
                                    <input type="number" name="price" min="1" class="form-control" id="priceInput"
                                           placeholder="<?= __("price"); ?>" required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="currencyInput"><?= __("currency"); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="glyphicon glyphicon-euro"></i>
                                    </div>
                                    <select class="form-control select2" name="currency" id="currencyInput"
                                            style="width: 100%;"
                                            required="required">
                                        <option value="AUD">AUD - Australian dollar</option>
                                        <option value="BRL">BRL - Brazilian real</option>
                                        <option value="CAD">CAD - Canadian dollar</option>
                                        <option value="CZK">CZK - Czech koruna</option>
                                        <option value="DKK">DKK - Danish krone</option>
                                        <option value="EUR">EUR - Euro</option>
                                        <option value="HKD">HKD - Hong Kong dollar</option>
                                        <option value="HUF">HUF - Hungarian forint</option>
                                        <option value="ILS">ILS - Israeli new shekel</option>
                                        <option value="JPY">JPY - Japanese yen</option>
                                        <option value="MYR">MYR - Malaysian ringgit</option>
                                        <option value="MXN">MXN - Mexican peso</option>
                                        <option value="TWD">TWD - New Taiwan dollar</option>
                                        <option value="NZD">NZD - New Zealand dollar</option>
                                        <option value="NOK">NOK - Norwegian krone</option>
                                        <option value="PHP">PHP - Philippine peso</option>
                                        <option value="PLN">PLN - Polish złoty</option>
                                        <option value="GBP">GBP - Pound sterling</option>
                                        <option value="RUB">RUB - Russian ruble</option>
                                        <option value="SGD">SGD - Singapore dollar</option>
                                        <option value="SEK">SEK - Swedish krona</option>
                                        <option value="CHF">CHF - Swiss franc</option>
                                        <option value="THB">THB - Thai baht</option>
                                        <option value="USD">USD - United States dollar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="frequencyInput"><?= __("frequency"); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="glyphicon glyphicon-time"></i>
                                    </div>
                                    <input type="number" name="frequency" min="1" max="100" value="1"
                                           class="form-control" id="frequencyInput"
                                           placeholder="<?= __("frequency"); ?>"
                                           required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="frequencyUnitInput"><?= __("frequency_unit"); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="glyphicon glyphicon-time"></i>
                                    </div>
                                    <select class="form-control select2" name="frequencyUnit" id="frequencyUnitInput"
                                            style="width: 100%;"
                                            required="required">
                                        <option value="DAY"><?= ucfirst(__("day")); ?></option>
                                        <option value="WEEK"><?= __("week"); ?></option>
                                        <option value="MONTH" selected><?= __("month") ?></option>
                                        <option value="YEAR"><?= __("year"); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="totalCyclesInput"><input type="checkbox" id="toggleTotalCyclesInput"
                                                                     onchange="disableInput('#toggleTotalCyclesInput', '#totalCyclesInput')">&nbsp;<?= __("total_cycles"); ?>
                                    <i class="fa fa-info-circle" data-toggle="tooltip"
                                       title="<?= __('tooltip_total_cycles'); ?>"></i></label>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-credit-card"></i>
                                    </div>
                                    <input type="number" name="totalCycles" class="form-control" id="totalCyclesInput"
                                           min="1"
                                           required="required" placeholder="<?= __("total_cycles"); ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" id="createPlanButton" name="createPlan" class="btn btn-primary"><i
                                        class="fa fa-user-plus"></i>&nbsp;<?= __("create"); ?></button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("plans"); ?></h3>
                        <button type="button" id="remove-selected" class="btn btn-danger pull-right" disabled><i
                                    class="icon fa fa-remove"></i>&nbsp;<?= __("remove"); ?></button>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="plans" class="table table-bordered table-striped ">
                            <thead>
                            <tr>
                                <th>
                                    <label>
                                        <input type="checkbox" id="check-all">
                                    </label>
                                </th>
                                <th><?= __("name"); ?></th>
                                <th><?= __("max_devices"); ?></th>
                                <th><?= __("max_contacts"); ?></th>
                                <th><?= __("credits"); ?></th>
                                <th><?= __("price"); ?></th>
                                <th><?= __("billing_cycle"); ?></th>
                                <th><?= __("total_cycles"); ?></th>
                                <th><?= __("enabled"); ?></th>
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
                        <h4 class="modal-title"><?= __("edit_plan") ?></h4>
                    </div>
                    <form id="editPlanForm">
                        <div class="modal-body">
                            <input type="hidden" name="planID" id="planIDEditInput" class="form-control"
                                   required="required">
                            <!--
                            <div class="form-group">
                                <label for="nameEditInput"><?= __("name"); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-address-card"></i>
                                    </div>
                                    <input type="text" name="name" maxlength="127" class="form-control" id="nameEditInput" required="required">
                                </div>
                            </div>
                            --!>
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
                                           id="devicesLimitEditInput" min="0" required="required">
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
                                           id="contactsLimitEditInput" min="0" required="required">
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
                                           min="1"
                                           required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="enabledEditInput"><?= __("enabled"); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-check-square"></i>
                                    </div>
                                    <select name="enabled" class="form-control select2" id="enabledEditInput"
                                            style="width: 100%">
                                        <option value="1"><?= __("yes"); ?></option>
                                        <option value="0"><?= __("no"); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i
                                        class="fa fa-remove"></i>&nbsp;<?= __("close") ?>
                            </button>
                            <button type="submit" id="editPlanButton" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;<?= __("save_changes") ?>
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

<script type="text/javascript">
    const removeSelected = $('#remove-selected');

    function toggleRemove() {
        if ($('input.remove-plans:checked').length > 0) {
            removeSelected.prop('disabled', false);
        } else {
            removeSelected.prop('disabled', true);
        }
    }

    function editPlan(name, credits, devicesLimit, contactsLimit, enabled, id) {
        let toggleCredits = $('#toggleCreditsEditInput');
        let toggleDevicesLimit = $('#toggleDevicesLimitEditInput');
        let toggleContactsLimit = $('#toggleContactsLimitEditInput');
        let enabledEditInput = $('#enabledEditInput');
        $('#nameEditInput').val(name);
        $('#planIDEditInput').val(id);
        enabledEditInput.val(enabled);
        enabledEditInput.trigger('change.select2');
        if (credits !== null) {
            toggleCredits.prop("checked", true);
            $('#creditsEditInput').val(credits);
        } else {
            toggleCredits.prop("checked", false);
            $('#creditsEditInput').val("");
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
        toggleDevicesLimit.trigger('change');
        toggleContactsLimit.trigger('change');
        toggleCredits.trigger('change');
        $('#modal-default').modal('show');
    }

    $(function () {
        const createPlanForm = $('#createPlanForm');
        const createPlanButton = $('#createPlanButton');
        const editPlanButton = $('#editPlanButton');
        const checkAllInput = $('#check-all');

        checkAllInput.click(function () {
            if ($(this).is(':checked')) {
                $('.remove-plans').prop('checked', true);
            } else {
                $('.remove-plans').prop('checked', false);
            }
            toggleRemove();
        });

        const table = $('#plans').DataTable({
            <?php if (isset($dataTablesLanguage)) { ?>
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/<?=$dataTablesLanguage?>.json"
            },
            <?php } ?>
            autoWidth: false,
            pagingType: "simple",
            responsive: true,
            ajax: "ajax/get-plans.php"
        });

        function reset() {
            $('.remove-plans').prop('checked', false);
            checkAllInput.prop('checked', false);
            removeSelected.prop('disabled', true);
        }

        function reloadPlans() {
            table.ajax.reload();
            reset();
        }

        $('#editPlanForm').submit(function (e) {
            e.preventDefault();
            let postData = $(this).serialize();
            let url = "ajax/edit-plan.php";
            editPlanButton.prop('disabled', true);
            const options = {positionClass: "toast-top-center", closeButton: true};
            ajaxRequest(url, postData).then(result => {
                toastr.success(result, null, options);
                reloadPlans();
                $('#modal-default').modal('hide');
            }).catch(reason => {
                toastr.error(reason, null, options);
            }).finally(() => {
                editPlanButton.prop('disabled', false);
            });
        });

        createPlanForm.submit(function (event) {
            event.preventDefault();
            let postData = createPlanForm.serialize();
            let url = "ajax/create-plan.php";
            createPlanButton.prop('disabled', true);
            ajaxRequest(url, postData).then(result => {
                toastr.success(result);
                reloadPlans();
                event.target.reset();
                $('#devicesLimitInput').prop('disabled', true);
                $('#contactsLimitInput').prop('disabled', true);
                $('#creditsInput').prop('disabled', true);
                $('#frequencyUnitInput').change();
                $('#currencyInput').change();
            }).catch(reason => {
                toastr.error(reason);
            }).finally(() => {
                createPlanButton.prop('disabled', false);
            });
        });

        $('#remove-selected').click(function (event) {
            let postData = table.$('input').serialize();
            if (postData) {
                let result = confirm("<?=__("remove_plans_confirmation");?>");
                if (result) {
                    let url = "ajax/remove-plans.php";
                    $(this).prop('disabled', true);
                    ajaxRequest(url, postData).then(result => {
                        toastr.success(result);
                        reloadPlans();
                    }).catch(reason => {
                        toastr.error("<?=__("error_unable_to_remove_plans");?>" + ` ${reason}`);
                        $(this).prop('disabled', false);
                    });
                }
            }
        });
    });
</script>
</body>
</html>