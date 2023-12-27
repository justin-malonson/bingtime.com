<?php
/**
 * @var User $logged_in_user
 */

require_once __DIR__ . "/includes/login.php";

$title = __("application_title") . " | " . __("contacts");

$contactsLists = ContactsList::where("userID", $logged_in_user->getID())->read_all();
$totalContactsLists = count($contactsLists);
$templates = Template::where("userID", $_SESSION["userID"])->read_all();
require_once __DIR__ . "/includes/header.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= __("contacts"); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("create_list"); ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" id="create-list" method="post">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="listNameInput"><?= __("list_name"); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-address-card"></i>
                                    </div>
                                    <input type="text" name="name" class="form-control" maxlength="25"
                                           id="listNameInput" placeholder="<?= __("list_name"); ?>" required="required">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" id="createListButton" name="createList" class="btn btn-primary"><i
                                        class="fa fa-save"></i>&nbsp;<?= __("create"); ?>
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->

                <?php if ($totalContactsLists > 0) { ?>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <div class="pull-left select-contacts-list">
                                <select title="<?= __("contacts_list"); ?>" class="form-control select2" id="contacts-list">
                                    <?php foreach ($contactsLists as $contactsList) {
                                        $name = htmlentities($contactsList->getName(), ENT_QUOTES);
                                        createOption("[{$contactsList->getID()}] {$name}", $contactsList->getID(), false, ["name" => $name]);
                                    } ?>
                                </select>
                            </div>
                            <div class="pull-right">
                                <button type="button" id="edit-list" style="margin-right: 4px" class="btn btn-primary">
                                    <i class="fa fa-edit"></i><span
                                            class="hidden-xs hidden-sm">&nbsp;<?= __("edit_list"); ?></span>
                                </button>
                                <button type="button" id="refresh-list" style="margin-right: 4px" class="btn btn-primary">
                                    <i class="fa fa-refresh"></i><span
                                            class="hidden-xs hidden-sm">&nbsp;<?= __("refresh"); ?></span>
                                </button>
                                <button type="button" id="remove-list" style="margin-right: 4px" class="btn btn-danger">
                                    <i class="fa fa-trash"></i><span
                                            class="hidden-xs hidden-sm">&nbsp;<?= __("remove_list"); ?></span>
                                </button>
                                <button type="button" id="export-list" class="btn btn-success"><i
                                            class="fa fa-file-excel-o"></i><span
                                            class="hidden-xs hidden-sm">&nbsp;<?= __("export"); ?></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="contacts" class="table table-bordered table-striped ">
                                <thead>
                                <tr>
                                    <th>
                                        <label>
                                            <input type="checkbox" id="check-all">
                                        </label>
                                    </th>
                                    <th><?= __("name"); ?></th>
                                    <th><?= __("mobile_number"); ?></th>
                                    <th><?= __("subscribed"); ?></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div>
                                <button type="button" style="margin-right: 4px" id="remove-selected"
                                        class="btn btn-danger" disabled="disabled"><i
                                            class="icon fa fa-remove"></i><span
                                            class="hidden-xs hidden-sm">&nbsp;<?= __("remove"); ?></span></button>
                                <button type="button" style="margin-right: 4px" class="btn btn-primary" id="move-selected"
                                        data-toggle="modal" data-target="#modal-move-contacts" disabled="disabled"><i
                                            class="icon fa fa-arrow-right"></i><span
                                            class="hidden-xs hidden-sm">&nbsp;<?= __("move"); ?></span></button>
                                <button type="button" style="margin-right: 4px" id="change-subscription-selected"
                                        class="btn btn-warning" disabled="disabled"><i
                                            class="icon fa fa-address-book"></i><span
                                            class="hidden-xs hidden-sm">&nbsp;<?= __("change_subscription"); ?></span>
                                </button>
                                <button type="button" style="margin-right: 4px" class="btn btn-primary"
                                        data-toggle="modal" data-target="#modal-add-contact"><i
                                            class="icon fa fa-plus"></i><span
                                            class="hidden-xs hidden-sm">&nbsp;<?= __("add_contact"); ?></span></button>
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#modal-import-contacts"><i
                                            class="icon fa fa-file-excel-o"></i><span
                                            class="hidden-xs hidden-sm">&nbsp;<?= __("import_contacts"); ?></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /.box -->

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?= __("send_message_to_contacts") ?></h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" id="send-message" method="post" enctype="multipart/form-data">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="contactsListInput"><?= __("contacts_list"); ?></label>
                                    <select class="form-control select2" name="contactsList" style="width: 100%;"
                                            id="contactsListInput"
                                            required="required">
                                        <?php foreach ($contactsLists as $contactsList) {
                                            $name = htmlentities($contactsList->getName(), ENT_QUOTES);
                                            createOption($name, $contactsList->getID(), false);
                                        } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="devicesInput"><?= __("device"); ?>
                                        <a href="#send-message" class="selectAllDevices" data-target="#devicesInput">
                                            <i class="fa fa-plus-square"></i>
                                        </a>
                                        <a href="#send-message" class="clearAllDevices" data-target="#devicesInput">
                                            <i class="fa fa-minus-square"></i>
                                        </a>
                                    </label>
                                    <select class="form-control select2" name="devices[]" id="devicesInput"
                                            style="width: 100%;"
                                            required="required" multiple="multiple">
                                        <?php
                                        /** @var User $logged_in_user */
                                        $selectedDevice = [$logged_in_user->getPrimaryDeviceID()];
                                        $logged_in_user->generateDeviceSimsList($selectedDevice);
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="scheduleInput"><input type="checkbox" id="toggleSchedule"
                                                                      onchange="disableInput('#toggleSchedule', '#scheduleInput')"> <?= __("schedule"); ?>
                                        <i class="fa fa-info-circle" data-toggle="tooltip"
                                           title="<?= __('tooltip_schedule'); ?>"></i>
                                    </label>
                                    <input type="datetime-local" class="form-control datetime-local" name="schedule"
                                           id="scheduleInput"
                                           required="required" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="prioritizeInput"><?= __("prioritize"); ?>&nbsp;<i class="fa fa-info-circle"
                                                                                                  data-toggle="tooltip"
                                                                                                  title="<?= __('tooltip_prioritize'); ?>"></i></label>
                                    <select name="prioritize" class="form-control select2" id="prioritizeInput" style="width: 100%;">
                                        <option value="0"><?= __("no") ?></option>
                                        <option value="1"><?= __("yes") ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="typeInput"><?= __("type"); ?>&nbsp;<i class="fa fa-info-circle"
                                                                                      data-toggle="tooltip"
                                                                                      title="<?= __('tooltip_mms'); ?>"></i></label>
                                    <select class="form-control select2 type-input" id="typeInput" name="type"
                                            data-target="#file-input" style="width: 100%;">
                                        <option value="sms">SMS</option>
                                        <option value="mms">MMS</option>
                                    </select>
                                </div>
                                <div class="form-group" id="file-input" hidden>
                                    <label for="attachmentsInput"><?= __("attachments"); ?>&nbsp;<i
                                                class="fa fa-info-circle"
                                                data-toggle="tooltip"
                                                title="<?= __('tooltip_attachments'); ?>"></i></label>
                                    <input type="file" name="attachments[]" accept=".jpg,.jpeg,.png,.gif,.aac,.3gp,.amr,.mp3,.m4a,.wav,.mp4,.txt,.vcf,.html"
                                           id="attachmentsInput" multiple>
                                </div>
                                <div class="form-group">
                                    <label for="templateInput"><?= __("template"); ?></label>
                                    <select class="form-control select2 template-input" id="templateInput"
                                            style="width: 100%;">
                                        <option value><?= __("none") ?></option>
                                        <?php
                                        foreach ($templates as $template) {
                                            createOption($template->getName(), $template->getMessage(), false);
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="messageInput"><?= __("message"); ?></label>
                                    <textarea class="form-control" id="messageInput" name="message" rows="4"
                                              placeholder="<?= __("contacts_message_placeholder"); ?>"></textarea>
                                </div>
                                <ul id="sms-counter" hidden="hidden">
                                    <li><?= __("encoding") ?>: <span class="encoding"></span></li>
                                    <li><?= __("length") ?>: <span class="length"></span></li>
                                    <li><?= __("messages") ?>: <span class="messages"></span></li>
                                    <li><?= __("per_message") ?>: <span class="per_message"></span></li>
                                    <li><?= __("remaining") ?>: <span class="remaining"></span></li>
                                </ul>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" id="sendMessageButton" name="send" style="margin-right: 4px"
                                        class="btn btn-primary"><i
                                            class="fa fa-send"></i>&nbsp;<?= __("send"); ?></button>
                                <button type="button" id="add-unsubscribe-link" class="btn btn-info"><i
                                            class="fa fa-link"></i>&nbsp;<?= __("add_unsubscribe_link"); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- /.box -->
                <?php } ?>

            </div>
        </div>
        <!-- /.row -->

        <?php if ($totalContactsLists > 0) { ?>
            <div class="modal fade" id="modal-add-contact">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><?= __("add_contact") ?></h4>
                        </div>
                        <form id="add-contact">
                            <div class="modal-body">
                                <div class="form-group" id="ContactName">
                                    <label for="nameInput"><?= __("name"); ?></label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <input type="text" name="name" class="form-control"
                                               id="nameInput" placeholder="<?= __("name"); ?>">
                                    </div>
                                </div>
                                <div class="form-group" id="ContactNumber">
                                    <label for="numberInput"><?= __("mobile_number"); ?></label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="glyphicon glyphicon-phone"></i>
                                        </div>
                                        <input type="text" name="number" class="form-control"
                                               id="numberInput" placeholder="<?= __("mobile_number"); ?>"
                                               required="required">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i
                                            class="fa fa-remove"></i>&nbsp;<?= __("close") ?>
                                </button>
                                <button type="submit" id="addContactButton" class="btn btn-primary"><i
                                            class="fa fa-save"></i>&nbsp;<?= __("create") ?>
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <div class="modal fade" id="modal-import-contacts">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><?= __("import_contacts") ?> [<a
                                        href="Contacts.xlsx"
                                        download="Contacts.xlsx"><?= __("download_example_excel"); ?></a>]</h4>
                        </div>
                        <form action="ajax/import-contacts.php">
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="dropzone">
                                        <div class="fallback">
                                            <input name="file" type="file" multiple/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <div class="modal fade" id="modal-edit-contacts-list">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><?= __("edit_contacts_list") ?></h4>
                        </div>
                        <form id="edit-contacts-list">
                            <div class="modal-body">
                                <input type="hidden" name="contactsListID" id="contactsListIDEditInput" class="form-control"
                                       required="required">
                                <div class="form-group">
                                    <label for="listNameEditInput"><?= __("list_name"); ?></label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-address-card"></i>
                                        </div>
                                        <input type="text" name="name" class="form-control" maxlength="25"
                                               id="listNameEditInput" placeholder="<?= __("list_name"); ?>" required="required">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i
                                            class="fa fa-remove"></i>&nbsp;<?= __("close") ?>
                                </button>
                                <button type="submit" id="editContactsListButton" class="btn btn-primary"><i
                                            class="fa fa-save"></i>&nbsp;<?= __("save") ?>
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <div class="modal fade" id="modal-move-contacts">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><?= __("move_contacts") ?></h4>
                        </div>
                        <form id="move-contacts">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="listIDInput"><?= __("contacts_list"); ?></label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-list"></i>
                                        </div>
                                        <select class="form-control select2" id="listIDInput" style="width: 100%">
                                            <?php foreach ($contactsLists as $contactsList) {
                                                $name = htmlentities($contactsList->getName(), ENT_QUOTES);
                                                createOption("[{$contactsList->getID()}] {$name}", $contactsList->getID(), false);
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i
                                            class="fa fa-remove"></i>&nbsp;<?= __("close") ?>
                                </button>
                                <button type="submit" id="moveContactsButton" class="btn btn-primary"><i
                                            class="fa fa-save"></i>&nbsp;<?= __("save") ?>
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

<script type="text/javascript">
    $(function () {
        const createContactsListForm = $('#create-list');
        const createListButton = $('#createListButton');

        createContactsListForm.submit(function (event) {
            event.preventDefault();
            let postData = $(this).serialize();
            let url = "ajax/create-contacts-list.php";
            createListButton.prop('disabled', true);
            ajaxRequest(url, postData).then(result => {
                toastr.success(result);
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }).catch(reason => {
                toastr.error(reason);
            }).finally(() => {
                createListButton.prop('disabled', false);
            });
        });
    });
</script>

<?php if ($totalContactsLists > 0) { ?>
    <?php require_once __DIR__ . "/includes/common-js.php"; ?>
    <?php require_once __DIR__ . "/includes/select-all.php"; ?>
    <script src="components/flatpickr/dist/flatpickr.min.js"></script>
    <script src="components/flatpickr/dist/plugins/minMaxTimePlugin.js"></script>

    <script type="text/javascript">
        <?php if(!isset($_COOKIE["DEVICE_ID"])) { ?>
        $(".datetime-local").flatpickr({
            enableTime: true,
            allowInput: true,
            disableMobile: true,
            plugins: [
                minMaxTimePlugin({
                    table: {
                        "today": {
                            minTime: new Date()
                        }
                    }
                })
            ]
        });
        <?php } ?>

        Dropzone.autoDiscover = false;

        const removeSelected = $('#remove-selected');
        const moveSelected = $('#move-selected');
        const changeSubscriptionSelected = $('#change-subscription-selected');

        function toggleOptions() {
            if ($('input.remove-contacts:checked').length > 0) {
                removeSelected.prop('disabled', false);
                moveSelected.prop('disabled', false);
                changeSubscriptionSelected.prop('disabled', false);
            } else {
                removeSelected.prop('disabled', true);
                moveSelected.prop('disabled', true);
                changeSubscriptionSelected.prop('disabled', true);
            }
        }

        $(function () {
            const checkAllInput = $('#check-all');
            const contactsList = $('#contacts-list');
            const messageInput = $('#messageInput');
            const addContactButton = $('#addContactButton');
            const sendMessageButton = $('#sendMessageButton');
            const editContactsListButton = $('#editContactsListButton');
            const moveContactsButton = $('#moveContactsButton');
            const contactsTable = $('#contacts');
            const toastrOptions = {positionClass: "toast-top-center", closeButton: true};

            function countMessage() {
                if (messageInput.val()) {
                    $('#sms-counter').prop('hidden', false);
                    messageInput.countSms('#sms-counter');
                } else {
                    $('#sms-counter').prop('hidden', true);
                }
            }

            messageInput.keyup(countMessage);

            messageInput.bind('paste', function () {
                setTimeout(function () {
                    countMessage(messageInput);
                });
            });

            $('#templateInput').change(function () {
                let value = $(this).val();
                messageInput.val(value);
                countMessage();
            });

            $('.type-input').change(function () {
                let fileInput = $($(this).data('target'));
                if ($(this).val() === "sms") {
                    fileInput.prop("hidden", true);
                } else {
                    fileInput.prop("hidden", false);
                }
            });

            $('#add-unsubscribe-link').click(function (e) {
                e.preventDefault();
                let message = '<?=__("unsubscribe_link", ["unsubscribeURL" => __("unsubscribe_url", ["server" => getServerURL()])]); ?>';
                message = message.replace("%listId%", $("#contactsListInput").val())
                if (messageInput.val().indexOf(message) === -1) {
                    messageInput.val(messageInput.val() + '\n\n' + message);
                    messageInput.trigger('keyup');
                }
            });

            $("#export-list").click(function (e) {
                document.location.href = "export-contacts.php?listID=" + contactsList.val();
            });

            $("#refresh-list").click(function (e) {
                reloadContacts();
            });

            checkAllInput.click(function () {
                if ($(this).is(':checked')) {
                    $('.remove-contacts').prop('checked', true);
                } else {
                    $('.remove-contacts').prop('checked', false);
                }
                toggleOptions();
            });

            const table = contactsTable.DataTable({
                <?php if (isset($dataTablesLanguage)) { ?>
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/<?=$dataTablesLanguage?>.json"
                },
                <?php } ?>
                autoWidth: false,
                serverSide: true,
                pagingType: "simple",
                responsive: true,
                order: [[ 1, "asc" ]],
                columnDefs: [
                    {
                        orderable: false,
                        targets: 0
                    }
                ],
                ajax: "ajax/get-contacts.php?contactsListID=" + contactsList.val()
            });

            function reset() {
                $('.remove-contacts').prop('checked', false);
                checkAllInput.prop('checked', false);
                removeSelected.prop('disabled', true);
                moveSelected.prop('disabled', true);
                changeSubscriptionSelected.prop('disabled', true);
            }

            contactsTable.on('page.dt length.dt', reset);

            $('#edit-list').click(function (event) {
                event.preventDefault();
                let selectedList = contactsList.find('option:selected');
                $('#contactsListIDEditInput').val(contactsList.val());
                $('#listNameEditInput').val(selectedList.data('name'));
                $('#modal-edit-contacts-list').modal('show');
            });

            contactsList.change(function () {
                table.ajax.url("ajax/get-contacts.php?contactsListID=" + contactsList.val()).load();
            });

            $('#add-contact').submit(function (e) {
                e.preventDefault();
                if (contactsList.val()) {
                    let postData = $('#add-contact').serialize() + '&listID=' + contactsList.val();
                    let url = "ajax/add-contact.php";
                    addContactButton.prop('disabled', true);
                    ajaxRequest(url, postData).then(result => {
                        toastr.success(result, null, toastrOptions);
                        $('form#add-contact').trigger("reset");
                        reloadContacts();
                        $('#modal-add-contact').modal('hide');
                    }).catch(reason => {
                        toastr.error(reason, null, toastrOptions);
                    }).finally(() => {
                        addContactButton.prop('disabled', false);
                    });
                }
            });

            $('#edit-contacts-list').submit(function (e) {
                e.preventDefault();
                let postData = $('#edit-contacts-list').serialize();
                let url = "ajax/edit-contacts-list.php";
                editContactsListButton.prop('disabled', true);
                ajaxRequest(url, postData).then(result => {
                    toastr.success(result, null, toastrOptions);
                    location.reload();
                    $('#modal-edit-contacts-list').modal('hide');
                }).catch(reason => {
                    toastr.error(reason, null, toastrOptions);
                }).finally(() => {
                    editContactsListButton.prop('disabled', false);
                });
            });

            $('#send-message').submit(function (e) {
                e.preventDefault();
                let postData = new FormData(this);
                let url = "ajax/send-message-contacts.php";
                sendMessageButton.prop('disabled', true);
                ajaxRequest(url, postData).then(result => {
                    toastr.success(result);
                    $('form#send-message').trigger("reset");
                    countMessage(messageInput);
                    $('#devicesInput').change();
                    $('#templateInput').change();
                    $('#typeInput').change();
                    $('#scheduleInput').prop("disabled", true);
                    $('#file-input').prop("hidden", true);
                }).catch(reason => {
                    toastr.error(reason);
                }).finally(() => {
                    sendMessageButton.prop('disabled', false);
                });
            });

            function reloadContacts() {
                table.ajax.reload();
                reset();
            }

            const dropZone = new Dropzone(".dropzone", {
                url: "./ajax/import-contacts.php",
                dictDefaultMessage: "<h3><?php if (isset($_COOKIE["DEVICE_ID"])) {
                    echo __("upload_excel_file_mobile");
                } else {
                    echo __("upload_excel_file");
                } ?></h3>",
                acceptedFiles: ".xlsx,.xls,.csv,.ods",
                uploadMultiple: false,
                parallelUploads: 1,
                timeout: 0,
                init: function () {
                    this.on('sending', function (file, xhr, formData) {
                        formData.append('listID', contactsList.val());
                    });
                    this.on("success", function (data, response) {
                        const obj = jQuery.parseJSON(response);
                        if (obj.error) {
                            toastr.error(obj.error, null, toastrOptions);
                        } else {
                            toastr.success(obj.result, null, toastrOptions);
                            reloadContacts();
                        }
                    });
                }
            });

            removeSelected.click(function () {
                let postData = table.$('input').serialize();
                if (postData) {
                    let result = confirm("<?=__("remove_contacts_confirmation");?>");
                    if (result) {
                        let url = "ajax/remove-contacts.php";
                        $(this).prop('disabled', true);
                        ajaxRequest(url, postData).then(result => {
                            toastr.success(result);
                            reloadContacts();
                        }).catch(reason => {
                            toastr.error(reason);
                            $(this).prop('disabled', false);
                        })
                    }
                }
            });

            $('#move-contacts').submit(function (e) {
                e.preventDefault();
                let postData = table.$('input').serialize() + '&listID=' + $('#listIDInput').val();
                if (postData) {
                    let result = confirm("<?=__("move_contacts_confirmation");?>");
                    if (result) {
                        let url = "ajax/move-contacts.php";
                        moveContactsButton.prop('disabled', true);
                        ajaxRequest(url, postData).then(result => {
                            toastr.success(result, null, toastrOptions);
                            reloadContacts();
                            $('#modal-move-contacts').modal('hide');
                        }).catch(reason => {
                            toastr.error(reason, null, toastrOptions);
                        }).finally(() => {
                            moveContactsButton.prop('disabled', false)
                        })
                    }
                }
            })

            changeSubscriptionSelected.click(function () {
                let postData = table.$('input').serialize() + '&listID=' + contactsList.val();
                if (postData) {
                    let result = confirm("<?=__("change_subscription_confirmation");?>");
                    if (result) {
                        let url = "ajax/change-subscription.php";
                        $(this).prop('disabled', true);
                        ajaxRequest(url, postData).then(result => {
                            toastr.success(result);
                            reloadContacts();
                        }).catch(reason => {
                            toastr.error(reason);
                            $(this).prop('disabled', false);
                        })
                    }
                }
            });

            $("#remove-list").click(function () {
                let postData = { id: contactsList.val() };
                let result = confirm("<?=__("remove_contacts_list_confirmation");?>");
                if (result) {
                    let url = "ajax/remove-contacts-list.php";
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
</body>
</html>