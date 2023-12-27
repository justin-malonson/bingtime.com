<?php
/**
 * @var User $logged_in_user
 */
require_once __DIR__ . "/includes/login.php";

$title = sprintf("%s | %s", __("application_title"), __("sender"));

$templates = Template::where("userID", $_SESSION["userID"])->read_all();
require_once __DIR__ . "/includes/header.php";
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= __("sender") ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-dismissible bg-primary" id="alert-send-message" style="display: none">
                    <button type="button" class="close" onclick="$('#alert-send-message').hide()" aria-hidden="true">
                        &times;
                    </button>
                    <h4><i class="icon fa fa-info"></i>&nbsp;<?= __("info") ?></h4>
                    <?= __("mass_mms_instructions") ?>
                </div>
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("send_message") ?></h3>
                        <i class="fa fa-info-circle" onclick="$('#alert-send-message').show();"></i>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" id="sendMessage" action="ajax/send-message.php" method="post"
                          enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="devicesInput"><?= __("devices"); ?>
                                    <a href="#sendMessage" class="selectAllDevices" data-target="#devicesInput">
                                        <i class="fa fa-plus-square"></i>
                                    </a>
                                    <a href="#sendMessage" class="clearAllDevices" data-target="#devicesInput">
                                        <i class="fa fa-minus-square"></i>
                                    </a>
                                </label>
                                <select class="form-control select2" id="devicesInput" name="devices[]"
                                        multiple="multiple"
                                        style="width: 100%;">
                                    <?php
                                    $selectedDevice = $logged_in_user->getPrimaryDeviceID();
                                    $logged_in_user->generateDeviceSimsList([$selectedDevice]);
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="mobileNumberInput"><?= __("mobile_numbers"); ?></label>
                                <input type="text" class="form-control" name="mobileNumber" id="mobileNumberInput"
                                       placeholder="<?= __("mobile_numbers_placeholder"); ?>"
                                   required="required">
                            </div>
                            <div class="form-group">
                                <label for="scheduleSingleInput">
                                    <input type="checkbox" id="toggleScheduleSingle"
                                           onchange="disableInput('#toggleScheduleSingle', '#scheduleSingleInput')">
                                    <?= __("schedule"); ?>
                                    <i class="fa fa-info-circle" data-toggle="tooltip"
                                       title="<?= __('tooltip_schedule'); ?>"></i>
                                </label>
                                <input type="datetime-local" class="form-control datetime-local" name="schedule"
                                       id="scheduleSingleInput"
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
                                <label for="attachmentsInput"><?= __("attachments"); ?>&nbsp;<i class="fa fa-info-circle"
                                                                                                data-toggle="tooltip"
                                                                                                title="<?= __('tooltip_attachments'); ?>"></i></label>
                                <input type="file" name="attachments[]" accept=".jpg,.jpeg,.png,.gif,.aac,.3gp,.amr,.mp3,.m4a,.wav,.mp4,.txt,.vcf,.html" id="attachmentsInput"
                                       multiple>
                            </div>
                            <div class="form-group">
                                <label for="templateInput"><?= __("template"); ?></label>
                                <select class="form-control select2 template-input" id="templateInput"
                                        data-target="#messageInput" style="width: 100%;">
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
                                <textarea class="form-control" id="messageInput" data-counter="#smsCounter" name="message" rows="4"
                                          placeholder="<?= __("message_placeholder", ["name" => "Lucy"]); ?>"></textarea>
                            </div>
                            <ul id="smsCounter" hidden="hidden">
                                <li><?= __("encoding") ?>: <span class="encoding"></span></li>
                                <li><?= __("length") ?>: <span class="length"></span></li>
                                <li><?= __("messages") ?>: <span class="messages"></span></li>
                                <li><?= __("per_message") ?>: <span class="per_message"></span></li>
                                <li><?= __("remaining") ?>: <span class="remaining"></span></li>
                            </ul>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" name="send" id="sendMessageButton" class="btn btn-primary"><i
                                        class="fa fa-send"></i>&nbsp;<?= __("send"); ?></button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
        </div>
        <!-- /.row -->

        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-dismissible bg-primary" id="alert-send-multiple-messages" style="display: none">
                    <button type="button" class="close" onclick="$('#alert-send-multiple-messages').hide()" aria-hidden="true">
                        &times;
                    </button>
                    <h4><i class="icon fa fa-info"></i>&nbsp;<?= __("info") ?></h4>
                    <?= __("mass_mms_instructions") ?>
                </div>
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("send_multiple_messages"); ?> [<a
                                    href="Example.xlsx" download="Example.xlsx"><?= __("download_example_excel"); ?></a>]
                            <i class="fa fa-info-circle" onclick="$('#alert-send-multiple-messages').show();"></i>
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form id="sendMessages" role="form" action="ajax/excel-upload.php" method="post"
                          enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="devicesMultipleInput"><?= __("devices"); ?>
                                    <a href="#sendMessages" class="selectAllDevices"
                                       data-target="#devicesMultipleInput">
                                        <i class="fa fa-plus-square"></i>
                                    </a>
                                    <a href="#sendMessages" class="clearAllDevices" data-target="#devicesMultipleInput">
                                        <i class="fa fa-minus-square"></i>
                                    </a>
                                </label>
                                <select class="form-control select2" id="devicesMultipleInput" name="devices[]"
                                        multiple="multiple"
                                        style="width: 100%;">
                                    <?php
                                    $logged_in_user->generateDeviceSimsList([$selectedDevice]);
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="scheduleMultipleInput"><input type="checkbox" id="toggleScheduleMultiple"
                                                                          onchange="disableInput('#toggleScheduleMultiple', '#scheduleMultipleInput')"> <?= __("schedule"); ?>
                                    <i class="fa fa-info-circle" data-toggle="tooltip"
                                       title="<?= __('tooltip_schedule'); ?>"></i>
                                </label>
                                <input type="datetime-local" class="form-control datetime-local" name="schedule"
                                       id="scheduleMultipleInput"
                                       required="required" disabled>
                            </div>
                            <div class="form-group">
                                <div class="dropzone">
                                    <div class="fallback">
                                        <input name="file" type="file" multiple/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="prioritizeMultipleInput"><?= __("prioritize"); ?>&nbsp;<i class="fa fa-info-circle"
                                                                                              data-toggle="tooltip"
                                                                                              title="<?= __('tooltip_prioritize'); ?>"></i></label>
                                <select name="prioritize" class="form-control select2" id="prioritizeMultipleInput" style="width: 100%;">
                                    <option value="0"><?= __("no") ?></option>
                                    <option value="1"><?= __("yes") ?></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="typeMultipleInput"><?= __("type"); ?>&nbsp;<i class="fa fa-info-circle"
                                                                                          data-toggle="tooltip"
                                                                                          title="<?= __('tooltip_mms'); ?>"></i></label>
                                <select class="form-control select2 type-input" id="typeMultipleInput" name="type"
                                        data-target="#file-multiple-input" style="width: 100%;">
                                    <option value="sms">SMS</option>
                                    <option value="mms">MMS</option>
                                </select>
                            </div>
                            <div class="form-group" id="file-multiple-input" hidden>
                                <label for="attachmentsMultipleInput"><?= __("attachments"); ?>&nbsp;<i
                                            class="fa fa-info-circle" data-toggle="tooltip"
                                            title="<?= __('tooltip_attachments'); ?>"></i></label>
                                <input type="file" name="attachments[]" accept=".jpg,.jpeg,.png,.gif,.aac,.3gp,.amr,.mp3,.m4a,.wav,.mp4,.txt,.vcf,.html"
                                       id="attachmentsMultipleInput" multiple>
                            </div>
                            <div class="form-group">
                                <label for="templateMultipleInput"><?= __("template"); ?></label>
                                <select class="form-control select2 template-input" id="templateMultipleInput"
                                        data-target="#messageMultipleInput" style="width: 100%;">
                                    <option value><?= __("none") ?></option>
                                    <?php
                                    foreach ($templates as $template) {
                                        createOption($template->getName(), $template->getMessage(), false);
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="messageMultipleInput"><?= __("message"); ?>
                                    <i class="fa fa-info-circle" data-toggle="tooltip"
                                       title="<?= __('tooltip_message_multiple'); ?>"></i></label>
                                <textarea class="form-control" id="messageMultipleInput" data-counter="#smsCounterMultiple" name="message" rows="4"
                                          placeholder="<?= __("excel_message_placeholder"); ?>"></textarea>
                            </div>
                            <ul id="smsCounterMultiple" hidden="hidden">
                                <li><?= __("encoding") ?>: <span class="encoding"></span></li>
                                <li><?= __("length") ?>: <span class="length"></span></li>
                                <li><?= __("messages") ?>: <span class="messages"></span></li>
                                <li><?= __("per_message") ?>: <span class="per_message"></span></li>
                                <li><?= __("remaining") ?>: <span class="remaining"></span></li>
                            </ul>
                        </div>

                        <div class="box-footer">
                            <button type="submit" name="send" id="sendMessagesButton" class="btn btn-primary"><i
                                        class="fa fa-send"></i>&nbsp;<?= __("send"); ?></button>
                        </div>
                    </form>
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
<?php require_once __DIR__ . "/includes/select-all.php"; ?>
<script src="components/flatpickr/dist/flatpickr.min.js"></script>
<script src="components/flatpickr/dist/plugins/minMaxTimePlugin.js"></script>

<script type="text/javascript">
    Dropzone.autoDiscover = false;

    $(function () {
        <?php if(!isset($_COOKIE["DEVICE_ID"])) { ?>
        $(".datetime-local").flatpickr({
            enableTime: true,
            allowInput: true,
            disableMobile: true,
            minDate: new Date(),
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

        let sendMessageButton = $('#sendMessageButton');
        let sendMessagesButton = $('#sendMessagesButton');
        let messageInput = $('#messageInput');
        let messageMultipleInput = $('#messageMultipleInput');

        function countMessage(input) {
            let counter = input.data('counter');
            if (input.val()) {
                $(counter).prop('hidden', false);
                input.countSms(counter);
            } else {
                $(counter).prop('hidden', true);
            }
        }

        $('.template-input').change(function () {
            let messageInput = $($(this).data('target'));
            let value = $(this).val();
            messageInput.val(value);
            countMessage(messageInput);
        });

        $('.type-input').change(function () {
            let fileInput = $($(this).data('target'));
            if ($(this).val() === "sms") {
                fileInput.prop("hidden", true);
            } else {
                fileInput.prop("hidden", false);
            }
        });

        messageInput.keyup(function () {
            countMessage($(this));
        });

        messageInput.bind('paste', function () {
            setTimeout(function () {
                countMessage(messageInput);
            });
        });

        messageMultipleInput.keyup(function () {
            countMessage($(this));
        });

        messageMultipleInput.bind('paste', function() {
            setTimeout(function () {
                countMessage(messageMultipleInput);
            });
        });

        let formDataAdditional;

        const dropZone = new Dropzone(".dropzone", {
            url: "./ajax/excel-upload.php",
            dictDefaultMessage: "<h3><?= isset($_COOKIE["DEVICE_ID"]) ? __("upload_excel_file_mobile") : __("upload_excel_file"); ?></h3>",
            acceptedFiles: ".xlsx,.xls,.csv,.ods",
            autoProcessQueue: false,
            uploadMultiple: false,
            parallelUploads: 1,
            timeout: 0,
            init: function () {
                this.on('sending', function (file, xhr, formData) {
                    if (formDataAdditional) {
                        for (let pair of formDataAdditional.entries()) {
                            if (pair[0] !== 'file') {
                                formData.append(pair[0], pair[1]);
                            }
                        }
                    }
                });
                this.on("success", function (data, response) {
                    response = JSON.parse(response);
                    if (response.error) {
                        toastr.error(response.error);
                    } else {
                        toastr.success(response.result);
                        $('form#sendMessages').trigger("reset");
                        countMessage(messageMultipleInput);
                        $('#devicesMultipleInput').change();
                        $('#templateMultipleInput').change();
                        $('#typeMultipleInput').change();
                        $('#scheduleMultipleInput').prop("disabled", true);
                        $('#file-multiple-input').prop("hidden", true);
                    }
                });
                this.on("complete", function (file) {
                    this.removeAllFiles();
                    sendMessagesButton.prop('disabled', false);
                });
            }
        });

        $('#sendMessages').submit(function (event) {
            event.preventDefault();
            formDataAdditional = new FormData(event.target);
            if (dropZone.getQueuedFiles().length > 0) {
                sendMessagesButton.prop('disabled', true);
                dropZone.processQueue();
            } else {
                toastr.error('<?= __("error_missing_excel_file"); ?>');
            }
        })

        $('#sendMessage').submit(function (event) {
            event.preventDefault();
            let formData = new FormData(this);
            let url = "ajax/send-message.php";
            sendMessageButton.prop('disabled', true);
            ajaxRequest(url, formData).then(result => {
                toastr.success(result);
                $('form#sendMessage').trigger("reset");
                countMessage(messageInput);
                $('#devicesInput').change();
                $('#simInput').change();
                $('#templateInput').change();
                $('#typeInput').change();
                $('#scheduleSingleInput').prop("disabled", true);
                $('#file-input').prop("hidden", true);
            }).catch(reason => {
                toastr.error(reason);
            }).finally(() => {
                sendMessageButton.prop('disabled', false);
            });
        });
    });
</script>
</body>
</html>