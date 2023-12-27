<?php
require_once __DIR__ . "/includes/login.php";

$title = __("application_title") . " | " . __("auto_responder");

require_once __DIR__ . "/includes/header.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= __("auto_responder"); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("add_response"); ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" id="addResponse" method="post">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="messageInput"><?= __("message"); ?></label>
                                <input type="text" name="message" class="form-control"
                                       id="messageInput" placeholder="<?= __("placeholder_auto_responder_message"); ?>"
                                       required="required">
                            </div>
                            <div class="form-group">
                                <label for="responseInput"><?= __("response"); ?></label>
                                <textarea class="form-control" id="responseInput" name="response" rows="4"
                                          placeholder="<?= __("message_placeholder", ["name" => "Lucy"]); ?>"
                                          required="required"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="matchTypeInput"><?= __("respond_if"); ?></label>
                                <select class="form-control select2" name="matchType" id="matchTypeInput"
                                        style="width: 100%">
                                    <option value="0"><?= __("exact_case_insensitive") ?></option>
                                    <option value="1"><?= __("exact_case_sensitive") ?></option>
                                    <option value="2"><?= __("contains") ?></option>
                                    <option value="3"><?= __("regular_expression") ?></option>
                                </select>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" id="addResponseButton" class="btn btn-primary"><i
                                        class="fa fa-plus"></i>&nbsp;<?= __("add_response"); ?>
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("responses"); ?></h3>
                        <button type="button" id="remove-selected" class="btn btn-danger pull-right" disabled><i
                                    class="icon fa fa-remove"></i>&nbsp;<?= __("remove"); ?></button>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="responses" class="table table-bordered table-striped ">
                            <thead>
                            <tr>
                                <th>
                                    <label>
                                        <input type="checkbox" id="check-all">
                                    </label>
                                </th>
                                <th><?= __("message"); ?></th>
                                <th><?= __("response"); ?></th>
                                <th><?= __("respond_if"); ?></th>
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
                        <h4 class="modal-title"><?= __("edit_response") ?></h4>
                    </div>
                    <form id="editResponseForm">
                        <div class="modal-body">
                            <input type="hidden" name="responseID" id="responseIDEditInput" class="form-control"
                                   required="required">

                            <div class="form-group">
                                <label for="messageEditInput"><?= __("message"); ?></label>
                                <input type="text" name="message" class="form-control"
                                       id="messageEditInput" placeholder="<?= __("message"); ?>" required="required">
                            </div>
                            <div class="form-group">
                                <label for="responseEditInput"><?= __("response"); ?></label>
                                <textarea class="form-control" id="responseEditInput" name="response" rows="4"
                                          placeholder="<?= __("response"); ?>"
                                          required="required"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="matchTypeEditInput"><?= __("respond_if"); ?></label>
                                <select class="form-control select2" name="matchType" id="matchTypeEditInput"
                                        style="width: 100%">
                                    <option value="0"><?= __("exact_case_insensitive") ?></option>
                                    <option value="1"><?= __("exact_case_sensitive") ?></option>
                                    <option value="2"><?= __("contains") ?></option>
                                    <option value="3"><?= __("regular_expression") ?></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="enabledEditInput"><?= __("enabled"); ?></label>
                                <select name="enabled" class="form-control select2" id="enabledEditInput"
                                        style="width: 100%">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i
                                        class="fa fa-remove"></i>&nbsp;<?= __("close") ?>
                            </button>
                            <button type="submit" id="editResponseButton" class="btn btn-primary"><i
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
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once __DIR__ . "/includes/footer.php"; ?>
<?php require_once __DIR__ . "/includes/common-js.php"; ?>

<script type="text/javascript">
    const removeSelected = $('#remove-selected');

    function toggleRemove() {
        if ($('input.remove-responses:checked').length > 0) {
            removeSelected.prop('disabled', false);
        } else {
            removeSelected.prop('disabled', true);
        }
    }

    $(function () {
        const addResponseForm = $('#addResponse');
        const addResponseButton = $('#addResponseButton');
        const editResponseButton = $('#editResponseButton');
        const checkAllInput = $('#check-all');
        const matchTypeEditInput = $('#matchTypeEditInput');
        const enabledEditInput = $('#enabledEditInput');
        const responsesTable = $('#responses');

        checkAllInput.click(function () {
            if ($(this).is(':checked')) {
                $('.remove-responses').prop('checked', true);
            } else {
                $('.remove-responses').prop('checked', false);
            }
            toggleRemove();
        });

        const table = responsesTable.DataTable({
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
            ajax: "ajax/get-responses.php",
            initComplete: initComplete,
        });

        function reset() {
            $('.remove-responses').prop('checked', false);
            checkAllInput.prop('checked', false);
            removeSelected.prop('disabled', true);
        }

        responsesTable.on('page.dt length.dt', reset);

        function initComplete() {
            $("#responses").off("click", ".edit-response").on("click", ".edit-response", function (event) {
                event.preventDefault();
                let id = $(this).data("id");
                let message = $(this).data("message");
                let response = $(this).data("response");
                let matchType = $(this).data("match-type");
                let enabled = $(this).data("enabled");
                $('#responseIDEditInput').val(id);
                $('#messageEditInput').val(message);
                $('#responseEditInput').val(response);
                enabledEditInput.val(enabled);
                enabledEditInput.trigger('change');
                matchTypeEditInput.val(matchType);
                matchTypeEditInput.trigger('change');
                $('#modal-default').modal('show');
            });
        }

        function reloadResponses() {
            table.ajax.reload(initComplete);
            reset();
        }

        addResponseForm.submit(function (event) {
            event.preventDefault();
            addResponseButton.prop("disabled", true);
            ajaxRequest("ajax/add-response.php", $(this).serialize()).then(result => {
                toastr.success(result);
                event.target.reset();
                $('#matchTypeInput').change();
                reloadResponses();
            }).catch(reason => {
                toastr.error(reason);
            }).finally(() => {
                addResponseButton.prop("disabled", false);
            })
        });

        $("#remove-selected").click(function () {
            let postData = table.$('input').serialize();
            if (postData) {
                let result = confirm("<?=__("remove_responses_confirmation");?>");
                if (result) {
                    let url = "ajax/remove-responses.php";
                    $(this).prop('disabled', true);
                    ajaxRequest(url, postData).then(result => {
                        toastr.success(result);
                        reloadResponses();
                    }).catch(reason => {
                        toastr.error("<?=__("error_unable_to_remove_responses");?>" + ` ${reason}`);
                        $(this).prop('disabled', false);
                    });
                }
            }
        });

        $('#editResponseForm').submit(function (event) {
            event.preventDefault();
            let url = "ajax/edit-response.php";
            let postData = $(this).serialize();
            const options = {positionClass: "toast-top-center", closeButton: true};
            editResponseButton.prop('disabled', true);
            ajaxRequest(url, postData).then(result => {
                toastr.success(result, null, options);
                reloadResponses();
                $('#modal-default').modal('hide');
            }).catch(reason => {
                toastr.error(reason, null, options);
            }).finally(() => {
                editResponseButton.prop('disabled', false);
            });
        });
    });
</script>
</body>
</html>