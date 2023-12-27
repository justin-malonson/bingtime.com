<?php
/**
 * @var User $logged_in_user
 */

require_once __DIR__ . "/includes/login.php";

$title = __("application_title") . " | " . __("templates");

require_once __DIR__ . "/includes/header.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= __("templates"); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="pull-left">
                            <h3 class="box-title"><?= __("manage_templates"); ?></h3>
                        </div>
                        <div class="pull-right">
                            <button type="button" id="add-template" class="btn btn-primary"><i
                                        class="icon fa fa-plus"></i><span
                                        class="hidden-xs hidden-sm">&nbsp;<?= __("add_template"); ?></span></button>
                            <button type="button" id="remove-selected" class="btn btn-danger" disabled><i
                                        class="icon fa fa-remove"></i><span
                                        class="hidden-xs hidden-sm">&nbsp;<?= __("remove"); ?></span></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="templates" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>
                                    <label>
                                        <input type="checkbox" id="check-all">
                                    </label>
                                </th>
                                <th><?= __("name"); ?></th>
                                <th><?= __("message"); ?></th>
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

        <div class="modal fade" id="modal-manage-template">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><?= __("add_template") ?></h4>
                    </div>
                    <form role="form" id="manageTemplateForm" method="post">
                        <div class="modal-body">
                            <input type="hidden" id="templateIDInput" name="templateID">
                            <div class="form-group">
                                <label for="nameInput"><?= __("name"); ?></label>
                                <input type="text" class="form-control" name="name" id="nameInput"
                                       placeholder="<?= __("name"); ?>"
                                       required="required">
                            </div>
                            <div class="form-group">
                                <label for="messageInput"><?= __("message"); ?></label>
                                <textarea class="form-control" id="messageInput" name="message" rows="4"
                                          placeholder="<?= __("message_placeholder"); ?>"
                                          required="required"></textarea>
                            </div>
                            <ul id="smsCounter" hidden="hidden">
                                <li>Encoding: <span class="encoding"></span></li>
                                <li>Length: <span class="length"></span></li>
                                <li>Messages: <span class="messages"></span></li>
                                <li>Per Message: <span class="per_message"></span></li>
                                <li>Remaining: <span class="remaining"></span></li>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i
                                        class="fa fa-remove"></i>&nbsp;<?= __("close") ?>
                            </button>
                            <button type="submit" name="create-template" id="saveTemplateButton" class="btn btn-primary"><i
                                        class="fa fa-save"></i>&nbsp;<?= __("save"); ?>
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

<script type="text/javascript">
    const removeSelected = $('#remove-selected');

    function toggleRemove() {
        if ($('input.remove-templates:checked').length > 0) {
            removeSelected.prop('disabled', false);
        } else {
            removeSelected.prop('disabled', true);
        }
    }

    $(function () {
        const checkAllInput = $('#check-all');
        const addTemplateButton = $('#add-template');
        const templateIDInput = $('#templateIDInput');
        const manageTemplateForm = $('#manageTemplateForm');
        const messageInput = $('#messageInput');
        const saveTemplateButton = $('#saveTemplateButton');
        const templatesTable = $('#templates');

        function initComplete() {
            $("#templates").on("click", ".edit-template", function (event) {
                event.preventDefault();
                let id = $(this).data("id");
                let name = $(this).data("name");
                let message = $(this).data("message");
                templateIDInput.prop('disabled', false);
                templateIDInput.val(id);
                $('#nameInput').val(name);
                $('#messageInput').val(message);
                showMessageSize();
                $('#modal-manage-template').modal('show');
            });
        }

        function showMessageSize() {
            if (messageInput.val()) {
                $('#smsCounter').prop('hidden', false);
                messageInput.countSms('#smsCounter');
            } else {
                $('#smsCounter').prop('hidden', true);
            }
        }

        messageInput.keyup(showMessageSize);

        messageInput.bind('paste', function () {
            setTimeout(function () {
                showMessageSize();
            });
        });

        addTemplateButton.click(function (event) {
            event.preventDefault();
            templateIDInput.prop('disabled', true);
            manageTemplateForm[0].reset();
            showMessageSize();
            $('#modal-manage-template').modal('show');
        });

        manageTemplateForm.submit(function (event) {
            event.preventDefault();
            let postData = manageTemplateForm.serialize();
            let url = "ajax/edit-template.php";
            if (templateIDInput.is(':disabled')) {
                url = "ajax/add-template.php";
            }
            saveTemplateButton.prop('disabled', true)
            const options = {positionClass: "toast-top-center", closeButton: true};
            ajaxRequest(url, postData).then(result => {
                toastr.success(result, null, options);
                reloadTemplates();
                if (templateIDInput.is(':disabled')) {
                    event.target.reset();
                }
                $('#modal-manage-template').modal('hide');
            }).catch(reason => {
                toastr.error(reason, null, options);
            }).finally(() => {
                saveTemplateButton.prop('disabled', false);
            });
        })

        checkAllInput.click(function () {
            if ($(this).is(':checked')) {
                $('.remove-templates').prop('checked', true);
            } else {
                $('.remove-templates').prop('checked', false);
            }
            toggleRemove();
        });

        const table = templatesTable.DataTable({
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
            ],
            ajax: "ajax/get-templates.php",
            initComplete: initComplete,
        });

        function reset() {
            $('.remove-templates').prop('checked', false);
            checkAllInput.prop('checked', false);
            removeSelected.prop('disabled', true);
        }

        templatesTable.on('page.dt length.dt', reset);

        function reloadTemplates() {
            table.ajax.reload(initComplete);
            reset();
        }

        removeSelected.click(function () {
            let postData = table.$('input').serialize();
            if (postData) {
                let result = confirm("<?=__("remove_templates_confirmation");?>");
                if (result) {
                    let url = "ajax/remove-templates.php";
                    $(this).prop('disabled', true);
                    ajaxRequest(url, postData).then(result => {
                        toastr.success(result);
                        reloadTemplates();
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