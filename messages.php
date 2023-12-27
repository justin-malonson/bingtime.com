<?php
require_once __DIR__ . "/includes/login.php";

$title = __("application_title") . " | " . __("messages");
$statusValues = array(__("status_all") => "", __("pending") => "Pending", __("queued") => "Queued", __("sent") => "Sent", __("delivered") => "Delivered", __("failed") => "Failed", __("received") => "Received", __("scheduled") => "Scheduled", __("canceled") => "Canceled");

if (isset($_REQUEST["startDate"]) && isset($_REQUEST["endDate"])) {
    $start_date = $_REQUEST["startDate"];
    $end_date = $_REQUEST["endDate"];
} else {
    $now = new DateTime();
    $now->setTimezone(new DateTimeZone($_SESSION["timeZone"]));
    $end_date = $now->format("Y-m-d");
    $start_date = $now->sub(new DateInterval('P7D'))->format("Y-m-d");
}

require_once __DIR__ . "/includes/user-devices.php";
require_once __DIR__ . "/includes/header.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= __("messages") ?>
            <span id="count"></span>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">

                    <form role="form" id="formMessages" autocomplete="off">
                        <div class="box-header with-border">
                            <div class="col-xs-8">
                                <h3 class="box-title" style="line-height: 2"><?= __("search_form_title") ?></h3>
                            </div>
                            <div class="col-xs-4">
                                <div class="pull-right">
                                    <select class="form-control select2" id="pageLimitInput" name="pageLimit"
                                            style="width: 75px" title="<?= __("page_limit") ?>">
                                        <option value="50">50</option>
                                        <option value="200">200</option>
                                        <option value="1000">1000</option>
                                        <?php if (!isset($_COOKIE["DEVICE_ID"])) { ?>
                                            <option value="2500">2500</option>
                                            <option value="5000">5000</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <!-- https://stackoverflow.com/a/19663826/1273550 -->
                        <div class="box-body">
                            <div class="form-group">

                                <?php $col = 6;
                                if ($_SESSION["isAdmin"]) {
                                    $col = 4 ?>
                                    <div class="col-lg-<?= $col ?>">
                                        <div class="form-group">
                                            <label for="selectUserInput"><?= __("select_user") ?></label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="glyphicon glyphicon-user"></i>
                                                </div>
                                                <select class="form-control select2" name="user" id="selectUserInput"
                                                        style="width: 100%;">
                                                    <option value='null'><?= __("all_users"); ?></option>
                                                    <?php
                                                    foreach ($users as $user) {
                                                        echo "<option value='{$user->getID()}' ";
                                                        if (isset($_REQUEST["user"]) && $_REQUEST["user"] == $user->getID()) {
                                                            echo "selected=selected";
                                                        }
                                                        $userName = strval($user);
                                                        echo ">{$userName}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="col-lg-<?= $col ?>">
                                    <div class="form-group">
                                        <label for="selectDeviceInput"><?= __("select_device") ?></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-android"></i>
                                            </div>
                                            <select class="form-control select2" name="device" id="selectDeviceInput"
                                                    style="width: 100%;">
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <?php $col = $_SESSION["isAdmin"] ? 2 : 3; ?>
                                <div class="col-lg-<?= $col ?>">
                                    <div class="form-group">
                                        <label for="selectStatusInput"><?= __("status"); ?></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-tag"></i>
                                            </div>
                                            <select class="form-control select2" name="status" id="selectStatusInput"
                                                    style="width: 100%;">
                                                <?php
                                                foreach ($statusValues as $status_label => $status) {
                                                    echo "<option value='{$status}' ";
                                                    if (isset($_REQUEST["status"]) && $_REQUEST["status"] == $status) {
                                                        echo "selected=selected";
                                                    }
                                                    echo ">{$status_label}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-<?= $col ?>">
                                    <div class="form-group">
                                        <label for="selectTypeInput"><?= __("type"); ?></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-columns"></i>
                                            </div>
                                            <select class="form-control select2" name="type" id="selectTypeInput"
                                                    style="width: 100%;">
                                                <option value=""><?= __("all"); ?></option>
                                                <option value="mms">MMS</option>
                                                <option value="sms">SMS</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="numberInput"><?= __("mobile_number"); ?></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="glyphicon glyphicon-phone"></i>
                                            </div>
                                            <input type="text" name="mobileNumber" class="form-control"
                                                   id="numberInput" placeholder="<?= __("mobile_number"); ?>"
                                                   value="<?php if (isset($_REQUEST["mobileNumber"])) echo $_REQUEST["mobileNumber"]; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="messageInput"><?= __("message"); ?></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-comments"></i>
                                            </div>
                                            <!-- https://stackoverflow.com/a/22700700/1273550 -->
                                            <textarea rows="1" name="message"
                                                      style="resize: vertical; min-height: 34px; overflow: hidden;"
                                                      class="form-control" id="messageInput"
                                                      placeholder="<?= __("message"); ?>"><?php if (isset($_REQUEST["message"])) echo $_REQUEST["message"]; ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="startDateInput"><?= __("start_date"); ?></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="date" name="startDate" class="form-control" id="startDateInput"
                                                   value="<?php echo $start_date; ?>" max="<?php echo $end_date; ?>"
                                                   required="required">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="endDateInput"><?= __("end_date"); ?></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="date" name="endDate" class="form-control" id="endDateInput"
                                                   value="<?php echo $end_date; ?>" min="<?php echo $start_date; ?>"
                                                   required="required">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <div class="col-xs-8">
                                <button type="submit" id="searchButton" name="search" style="margin-right: 4px"
                                        class="btn btn-primary"><i
                                            class="icon fa fa-search"></i><span
                                            class="hidden-sm hidden-xs">&nbsp;<?= __("search"); ?></span></button>
                                <button type="button" id="exportCSV" style="margin-right: 4px" class="btn btn-success">
                                    <i class="icon fa fa-file-excel-o"></i><span
                                            class="hidden-sm hidden-xs">&nbsp;<?= __("export"); ?></span></button>
                                <button type="button" id="resend" style="margin-right: 4px" class="btn btn-primary"><i
                                            class="icon fa fa-send"></i><span
                                            class="hidden-sm hidden-xs">&nbsp;<?= __("resend"); ?></span></button>
                                <button type="button" id="remove" class="btn btn-danger"><i
                                            class="icon fa fa-remove"></i><span
                                            class="hidden-sm hidden-xs">&nbsp;<?= __("remove"); ?></span></button>
                            </div>
                            <div class="col-xs-4">
                                <div class="pull-right">
                                    <select class="form-control select2" id="selectPageInput" name="page"
                                            title="<?= __("page_no"); ?>" style="width: 75px">
                                        <option value="1">1</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.box -->

                <label for="check-all" class="pull-right" style="position:relative; z-index: 10;">
                    <input type="checkbox" id="check-all">
                    &nbsp;<?= __("select_all"); ?>
                </label>
                <div id="messages">

                </div>
            </div>
            <!-- /.col -->
        </div>

        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><?= __("send_message") ?></h4>
                    </div>
                    <form id="reply-message">
                        <div class="modal-body">
                            <input type="hidden" name="devices" id="devicesReplyInput" class="form-control"
                                   required="required">
                            <input type="hidden" name="prioritize" value="1" id="prioritizeReplyInput" class="form-control"
                                   required="required">
                            <div class="form-group">
                                <label for="numberReplyInput"><?= __("mobile_numbers"); ?></label>
                                <input type="text" class="form-control" name="mobileNumber" id="numberReplyInput"
                                       placeholder="<?= __("mobile_numbers_placeholder"); ?>"
                                       required="required">
                            </div>
                            <div class="form-group">
                                <label for="typeInput"><?= __("type"); ?>&nbsp;<i class="fa fa-info-circle"
                                                                                  data-toggle="tooltip"
                                                                                  data-placement="bottom"
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
                                       id="attachmentsInput"
                                       multiple>
                            </div>
                            <div class="form-group">
                                <label for="messageReplyInput"><?= __("message"); ?></label>
                                <textarea class="form-control" id="messageReplyInput" data-counter="#smsCounter"
                                          name="message" rows="4"
                                          placeholder="<?= __("message"); ?>"></textarea>
                            </div>
                            <ul id="smsCounter" hidden="hidden">
                                <li><?= __("encoding") ?>: <span class="encoding"></span></li>
                                <li><?= __("length") ?>: <span class="length"></span></li>
                                <li><?= __("messages") ?>: <span class="messages"></span></li>
                                <li><?= __("per_message") ?>: <span class="per_message"></span></li>
                                <li><?= __("remaining") ?>: <span class="remaining"></span></li>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i
                                        class="fa fa-remove"></i>&nbsp;<?= __("close") ?>
                            </button>
                            <button type="submit" id="replyMessageButton" class="btn btn-primary"><i
                                        class="fa fa-send"></i>&nbsp;<?= __("send") ?>
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

<script>
    $(function () {
        const startDateInput = $('#startDateInput');
        const endDateInput = $('#endDateInput');
        const selectPageInput = $('#selectPageInput');
        const formMessages = $('#formMessages');
        const checkAllInput = $('#check-all');
        const replyMessageButton = $('#replyMessageButton');
        const searchButton = $('#searchButton');
        const pageLimitInput = $('#pageLimitInput');
        let messageReplyInput = $('#messageReplyInput');

        function countMessage(input) {
            let counter = input.data('counter');
            if (input.val()) {
                $(counter).prop('hidden', false);
                input.countSms(counter);
            } else {
                $(counter).prop('hidden', true);
            }
        }

        $('.type-input').change(function () {
            let fileInput = $($(this).data('target'));
            if ($(this).val() === "sms") {
                fileInput.prop("hidden", true);
            } else {
                fileInput.prop("hidden", false);
            }
        });

        messageReplyInput.keyup(function () {
            countMessage($(this));
        });

        messageReplyInput.bind('paste', function () {
            setTimeout(function () {
                countMessage(messageReplyInput);
            });
        });

        $('#reply-message').submit(function (event) {
            event.preventDefault();
            let formData = new FormData(this);
            let url = "ajax/send-message.php";
            replyMessageButton.prop('disabled', true);
            const options = {positionClass: "toast-top-center", closeButton: true};
            ajaxRequest(url, formData).then(result => {
                toastr.success(result, null, options);
                $(this).trigger("reset");
                $('#typeInput').change();
                $('#file-input').prop("hidden", true);
                getMessages();
                $('#modal-default').modal('hide');
            }).catch(reason => {
                toastr.error(reason, null, options);
            }).finally(() => {
                replyMessageButton.prop('disabled', false);
            });
        })

        startDateInput.change(function () {
            if (document.getElementById("startDateInput").checkValidity()) {
                let startDate = new Date(startDateInput.val());
                let endDate = new Date(endDateInput.val());
                if (startDate > endDate) {
                    endDateInput.val(startDateInput.val());
                }
                endDateInput.attr('min', startDateInput.val());
            }
        });

        endDateInput.change(function () {
            if (document.getElementById("endDateInput").checkValidity()) {
                let startDate = new Date(startDateInput.val());
                let endDate = new Date(endDateInput.val());
                if (endDate < startDate) {
                    startDateInput.val(endDateInput.val());
                }
                startDateInput.attr('max', endDateInput.val());
            }
        });

        $("#exportCSV").click(function (e) {
            document.location.href = "export.php?" + formMessages.serialize();
        });

        checkAllInput.click(function () {
            if ($(this).is(':checked')) {
                $('.resend-checkbox').prop('checked', true);
            } else {
                $('.resend-checkbox').prop('checked', false);
            }
        });

        selectPageInput.change(function () {
            getMessages();
        });

        pageLimitInput.change(function () {
            selectPageInput.val(1);
            getMessages();
        });

        let devices = <?php echo json_encode($data, JSON_FORCE_OBJECT); ?>;

        function generateDevices(userId) {
            let selectDeviceInput = $('#selectDeviceInput');
            selectDeviceInput.html('');
            selectDeviceInput.append('<option value="null"><?=__("all_devices")?></option>');
            if (userId) {
                $.each(devices[userId], function (val, label) {
                    let selected = '';
                    <?php if(isset($_REQUEST["device"])) { ?>
                    if (val === '<?=$_REQUEST["device"]?>') {
                        selected = 'selected="selected"';
                    }
                    <?php } ?>
                    selectDeviceInput.append(`<option value="${val}" ${selected}>${label}</option>`);
                });
            }
        }

        let defaultUser = <?php echo $_SESSION["userID"]?>;
        <?php if($_SESSION["isAdmin"]) { ?>

        const selectUserInput = $('#selectUserInput');
        selectUserInput.change(function () {
            generateDevices(this.value)
        });

        defaultUser = selectUserInput.val();
        <?php } ?>

        generateDevices(defaultUser);

        function getSelectedMessages() {
            let messages = [];
            const checkboxes = document.querySelectorAll('input[name=messages]:checked');
            for (let i = 0; i < checkboxes.length; i++) {
                messages.push(checkboxes[i].value)
            }
            return messages;
        }

        function asyncCall(url, postData, button) {
            ajaxRequest(url, postData).then(result => {
                toastr.success(result);
                formMessages.trigger('submit');
                checkAllInput.prop('checked', false);
                button.prop("disabled", true);
            }).catch(reason => {
                toastr.error("<?=__("error_dialog_message");?>" + ` ${reason}`);
            }).finally(() => {
                button.prop("disabled", false);
            });
        }

        function range(size, startAt = 0) {
            return [...Array(size).keys()].map(i => i + startAt);
        }

        formMessages.submit(function (e) {
            e.preventDefault();
            selectPageInput.val(1);
            getMessages();
        });

        function getMessages() {
            let url = "ajax/retrieve-messages.php";
            let postData = formMessages.serialize();
            history.replaceState(null, null, "?" + postData);
            selectPageInput.prop('disabled', true);
            pageLimitInput.prop('disabled', true);
            searchButton.prop('disabled', true);
            ajaxRequest(url, postData).then(result => {
                checkAllInput.prop('checked', false);
                let pages = [1];
                const selectedPage = selectPageInput.val();
                if (result.totalCount != 0) {
                    let start = 1;
                    let end = result.totalCount;
                    let pageNo = selectPageInput.val();
                    if (pageNo != 0) {
                        if (result.totalCount) {
                            start = Math.max((pageNo - 1) * result.pageLimit + 1, 1);
                            end = pageNo * result.pageLimit;
                            if (end > result.totalCount) {
                                end = result.totalCount;
                            }
                        }
                    }
                    if (result.totalPages > 0) {
                        pages = range(result.totalPages, 1);
                    }
                    $('#count').text(`(<?=__("messages_count")?>)`);
                } else {
                    $('#count').text("(<?=__("no_messages_found")?>)");
                }
                $('#messages').html(result.messages);
                selectPageInput.html('');
                $.each(pages, function (index, val) {
                    let selected = false;
                    if (val == selectedPage) {
                        selected = true;
                    }
                    selectPageInput.append($("<option>").prop('selected', selected).attr('value', val).text(val));
                });
                $('.reply-message').click(function (event) {
                    event.preventDefault();
                    $('#numberReplyInput').val($(this).data("number"));
                    let sim = $(this).data("sim");
                    let device = $(this).data("device");
                    if (sim !== "") {
                        $('#devicesReplyInput').val(`${device}|${sim}`);
                    } else {
                        $('#devicesReplyInput').val(device);
                    }
                    $('#modal-default').modal('show');
                });
            }).catch(reason => {
                toastr.error("<?=__("error_dialog_message");?>" + ` ${reason}`);
                $('#messages').html("");
                $('#count').text("");
            }).finally(() => {
                selectPageInput.prop('disabled', false);
                pageLimitInput.prop('disabled', false);
                searchButton.prop('disabled', false);
            });
        }

        getMessages();

        $("#resend").click(function (e) {
            let url = "ajax/resend.php"; // the script where you handle the form input.
            let messages = getSelectedMessages();
            if (messages.length > 0) {
                let postData = { messages: JSON.stringify(messages) };
                if (postData) {
                    asyncCall(url, postData, $(this));
                }
            }
        });

        $("#remove").click(function (e) {
            let url = "ajax/remove-messages.php";
            let messages = getSelectedMessages();
            if (messages.length > 0) {
                let postData = { messages: JSON.stringify(messages) };
                if (postData) {
                    let result = confirm("<?=__("remove_messages_confirmation");?>");
                    if (result) {
                        asyncCall(url, postData, $(this));
                    }
                }
            }
        });
    })
</script>
</body>
</html>