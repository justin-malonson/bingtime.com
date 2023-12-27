<?php
/**
 * @var User $logged_in_user
 */

require_once __DIR__ . "/includes/login.php";

$title = __("application_title") . " | " . __("blacklist");
$users = User::read_all();

require_once __DIR__ . "/includes/header.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= __("blacklist"); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="callout callout-info">
                            <h4 style="color: black"><?= __("hint"); ?></h4>
                            <p style="color: black"><?= __("blacklist_instruction", ["userID" => $logged_in_user->getID()]); ?></p>
                        </div>
                        <div class="pull-left">
                            <?php if ($_SESSION["isAdmin"]) { ?>
                                <select title="<?= __("user"); ?>" class="form-control select2" id="user">
                                    <?php
                                    foreach ($users as $user) {
                                        createOption(strval($user), $user->getID(), $user->getID() == $_SESSION["userID"]);
                                    }
                                    ?>
                                </select>
                            <?php } else { ?>
                                <h3 class="box-title"><?= __("blacklist"); ?></h3>
                            <?php } ?>
                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-primary"
                                    data-toggle="modal" data-target="#modal-add"><i
                                    class="icon fa fa-plus"></i><span
                                    class="hidden-xs hidden-sm" data-toggle="">&nbsp;<?= __("add"); ?></span></button>
                            <button type="button" id="remove-selected" class="btn btn-danger" disabled><i
                                    class="icon fa fa-remove"></i><span
                                    class="hidden-xs hidden-sm">&nbsp;<?= __("remove"); ?></span></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="blacklist" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>
                                    <label>
                                        <input type="checkbox" id="check-all">
                                    </label>
                                </th>
                                <th><?= __("mobile_number"); ?></th>
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

        <div class="modal fade" id="modal-add">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><?= __("add") ?></h4>
                    </div>
                    <form role="form" id="addForm" method="post">
                        <div class="modal-body">
                            <input type="hidden" id="idInput" name="id">
                            <div class="form-group">
                                <label for="mobileNumbersInput"><?= __("mobile_numbers"); ?></label>
                                <textarea rows="4" class="form-control" name="numbers" id="mobileNumbersInput"
                                       placeholder="<?= __("add_numbers_blacklist_placeholder"); ?>"
                                          required="required"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i
                                        class="fa fa-remove"></i>&nbsp;<?= __("close") ?>
                            </button>
                            <button type="submit" name="add" id="addButton" class="btn btn-primary"><i
                                        class="fa fa-save"></i>&nbsp;<?= __("add"); ?>
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
        if ($('input.remove-numbers:checked').length > 0) {
            removeSelected.prop('disabled', false);
        } else {
            removeSelected.prop('disabled', true);
        }
    }

    $(function () {
        const checkAllInput = $('#check-all');
        const addButton = $('#addButton');
        const blacklistTable = $('#blacklist');
        const addForm = $('#addForm');

        addForm.submit(function (event) {
            event.preventDefault();
            let postData = addForm.serialize();
            let url = "ajax/add-blacklist-number.php"<?php if ($_SESSION["isAdmin"]) { ?>  +  '?user=' + $("#user").val(); <?php } ?>;
            addButton.prop('disabled', true);
            const options = {positionClass: "toast-top-center", closeButton: true};
            ajaxRequest(url, postData).then(result => {
                toastr.success(result, null, options);
                reloadBlacklist();
                event.target.reset();
                $('#modal-add').modal('hide');
            }).catch(reason => {
                toastr.error(reason, null, options);
            }).finally(() => {
                addButton.prop('disabled', false);
            });
        })

        checkAllInput.click(function () {
            if ($(this).is(':checked')) {
                $('.remove-numbers').prop('checked', true);
            } else {
                $('.remove-numbers').prop('checked', false);
            }
            toggleRemove();
        });

        const table = blacklistTable.DataTable({
            <?php if (isset($dataTablesLanguage)) { ?>
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/<?=$dataTablesLanguage?>.json"
            },
            <?php } ?>
            autoWidth: false,
            pagingType: "simple",
            responsive: true,
            columnDefs: [
                {
                    orderable: false,
                    targets: 0
                }
            ]
        });

        <?php if ($_SESSION["isAdmin"]) { ?>
        let selectUser = $("#user");
        selectUser.change(function () {
            if (table.ajax.url()) {
                table.ajax.url("ajax/get-blacklist.php?user=" + $(this).val()).load();
            } else {
                table.ajax.url("ajax/get-blacklist.php?user=" + $(this).val())
            }
            reset();
        });
        selectUser.change();
        <?php } else { ?>
        table.ajax.url("ajax/get-blacklist.php")
        <?php } ?>

        function reset() {
            $('.remove-numbers').prop('checked', false);
            checkAllInput.prop('checked', false);
            removeSelected.prop('disabled', true);
        }

        blacklistTable.on('page.dt length.dt', reset);

        function reloadBlacklist() {
            table.ajax.reload();
            reset();
        }

        removeSelected.click(function () {
            let postData = table.$('input').serialize();
            if (postData) {
                let result = confirm("<?=__("remove_number_from_black_list_confirmation");?>");
                if (result) {
                    let url = "ajax/remove-blacklist-number.php";
                    $(this).prop('disabled', true);
                    ajaxRequest(url, postData).then(result => {
                        toastr.success(result);
                        reloadBlacklist();
                    }).catch(reason => {
                        toastr.error(reason);
                        $(this).prop('disabled', false);
                    })
                }
            }
        });
    });
</script>
</body>
</html>