<?php
/**
 * @var User $logged_in_user
 */

require_once __DIR__ . "/includes/login.php";

$title = __("application_title") . " | " . __("profile");
$languageFiles = getLanguageFiles();
if ($logged_in_user->getSleepTime() != null) {
    $sleepTime = explode('-', $logged_in_user->getSleepTime());
    $sleepTimeFrom = $sleepTime[0];
    $sleepTimeTo = $sleepTime[1];
}
require_once __DIR__ . "/includes/header.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= __("profile"); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("change_password"); ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" id="change-password" method="post">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="currentPasswordInput"><?= __("current_password"); ?></label>
                                <input type="password" name="currentPassword" class="form-control" minlength="8"
                                       id="currentPasswordInput" placeholder="<?= __("current_password"); ?>"
                                       required="required">
                            </div>
                            <div class="form-group">
                                <label for="newPasswordInput"><?= __("new_password"); ?></label>
                                <input type="password" name="newPassword" class="form-control" minlength="8"
                                       id="newPasswordInput" placeholder="<?= __("password"); ?>" required="required">
                            </div>
                            <div class="form-group" id="confirmPasswordBox">
                                <label for="confirmPasswordInput"><?= __("confirm_password"); ?></label>
                                <input type="password" name="confirmPassword" class="form-control"
                                       id="confirmPasswordInput" placeholder="<?= __("confirm_password"); ?>"
                                       required="required">
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" id="changePasswordButton" name="changePassword"
                                    class="btn btn-primary"><i
                                        class="fa fa-edit"></i>&nbsp;<?= __("change_password"); ?>
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("settings"); ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" id="settings" method="post">
                        <div class="box-body">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title"><?= __("general"); ?></div>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="nameInput"><?= __("name"); ?></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="glyphicon glyphicon-user"></i>
                                            </div>
                                            <input type="text" name="name" class="form-control" id="nameInput"
                                                   value="<?= htmlentities($logged_in_user->getName(), ENT_QUOTES); ?>" placeholder="<?= __("name"); ?>"
                                                   required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="timeZoneInput"><?= __("timezone") ?></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-globe"></i>
                                            </div>
                                            <select class="form-control select2" id="timeZoneInput" name="timezone"
                                                    style="width: 100%;" required="required">
                                                <?php
                                                $timezones = generate_timezone_list();
                                                foreach ($timezones as $timezone => $timezone_value) {
                                                    echo "<option value='$timezone' ";
                                                    if ($logged_in_user->getTimeZone() == $timezone) {
                                                        echo "selected='selected'";
                                                    }
                                                    echo ">{$timezone_value}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php if (count($languageFiles) > 1) { ?>
                                        <div class="form-group">
                                            <label for="languageInput"><?= __("language") ?></label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-language"></i>
                                                </div>
                                                <select name="language" id="languageInput"
                                                        class="form-control select2"
                                                        style="width: 100%">
                                                    <?php
                                                    foreach ($languageFiles as $languageFile) {
                                                        createOption(ucfirst($languageFile), $languageFile, $languageFile === $logged_in_user->getLanguage());
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title"><?= __("messages"); ?></div>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="delayInput">
                                            <?= __("delay_setting"); ?>
                                            <i class="fa fa-info-circle" data-toggle="tooltip"
                                               title="<?= __('tooltip_delay'); ?>"></i>
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input type="text" name="delay" class="form-control"
                                                   id="delayInput"
                                                   value="<?= $logged_in_user->getDelay(); ?>" placeholder="<?= __("delay"); ?>"
                                                   required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="receivedSmsEmailInput">
                                            <input <?= Setting::get("sms_to_email_enabled") ? false : "disabled"; ?> type="checkbox" name="smsToEmail" value="1" id="toggleReceivedSmsEmailInput"
                                                   onchange="disableInput('#toggleReceivedSmsEmailInput', '#receivedSmsEmailInput')" <?php if ($logged_in_user->getSmsToEmail()) echo "checked='checked'" ?>>
                                            <?= __("send_received_messages_to_email"); ?> <?= Setting::get("sms_to_email_enabled") ? false : "(" . __('disabled_by_admin') . ")" ?>
                                        </label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-mail-forward"></i>
                                            </div>
                                            <input type="email" name="receivedSmsEmail" class="form-control"
                                                   id="receivedSmsEmailInput"
                                                   value="<?= $logged_in_user->getReceivedSmsEmail() ?: $logged_in_user->getEmail(); ?>"
                                                   required="required" <?php if (!$logged_in_user->getSmsToEmail()) echo "disabled" ?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="toggleSleepTimeInput">
                                            <input type="checkbox" id="toggleSleepTimeInput"
                                                   onchange="disableInput('#toggleSleepTimeInput', '.sleepTimeInput')" <?php if (!empty($logged_in_user->getSleepTime())) echo "checked='checked'" ?>>
                                            <?= __("sleep_time"); ?>
                                            <i class="fa fa-info-circle" data-toggle="tooltip"
                                               title="<?= __('tooltip_sleep_time'); ?>"></i>
                                        </label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input aria-label="<?= __("sleep_time_from"); ?>" type="time" name="sleepTimeFrom"
                                                   style="width: 125px" class="form-control sleepTimeInput"
                                                   id="sleepTimeFromInput"
                                                   value="<?= $sleepTimeFrom ?? ""; ?>"
                                                   required="required" <?php if (empty($logged_in_user->getSleepTime())) echo "disabled" ?>>
                                            <input aria-label="<?= __("sleep_time_upto"); ?>" type="time" name="sleepTimeTo"
                                                   style="width: 125px" class="form-control sleepTimeInput"
                                                   id="sleepTimeToInput"
                                                   value="<?= $sleepTimeTo ?? ""; ?>"
                                                   required="required" <?php if (empty($logged_in_user->getSleepTime())) echo "disabled" ?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="reportDeliveryInput">
                                            <input type="checkbox" name="reportDelivery" value="1"
                                                   id="reportDeliveryInput" <?php if ($logged_in_user->getReportDelivery()) echo "checked='checked'" ?>>
                                            <?= __("report_delivery_setting"); ?>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label for="useProgressiveQueueInput">
                                            <input type="checkbox" name="useProgressiveQueue" value="1"
                                                   id="useProgressiveQueueInput" <?php if ($logged_in_user->isUseProgressiveQueue()) echo "checked='checked'" ?>>
                                            <?= __("use_progressive_queue_setting"); ?>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label for="autoRetryInput">
                                            <input type="checkbox" name="autoRetry" value="1"
                                                   id="autoRetryInput" <?php if ($logged_in_user->getAutoRetry()) echo "checked='checked'" ?>>
                                            <?= __("auto_retry_setting"); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title"><?= __("ussd"); ?></div>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="ussdDelayInput">
                                            <?= __("ussd_delay"); ?>
                                            <i class="fa fa-info-circle" data-toggle="tooltip"
                                               title="<?= __('tooltip_ussd_delay'); ?>"></i>
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input type="number" name="ussdDelay" class="form-control"
                                                   id="ussdDelayInput"
                                                   min="0" max="120"
                                                   value="<?= $logged_in_user->getUssdDelay(); ?>" placeholder="<?= __("delay"); ?>"
                                                   required="required">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" id="saveSettingsButton" name="saveSettings" class="btn btn-primary"><i
                                        class="fa fa-save"></i>&nbsp;<?= __("save"); ?>
                            </button>
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
<?php require_once __DIR__ . "/includes/common-js.php" ?>
<script src="components/he/he.js"></script>
<script type="text/javascript">

    $(function () {
        const changePasswordForm = $("#change-password");
        const settingsForm = $("#settings");
        const changePasswordButton = $('#changePasswordButton');
        const saveSettingsButton = $('#saveSettingsButton');
        const languageInput = $('#languageInput');

        changePasswordForm.validate({
            rules: {
                newPassword: "required",
                confirmPassword: {
                    equalTo: "#newPasswordInput"
                }
            },
            submitHandler: function (form) {
                let postData = changePasswordForm.serialize();
                let url = "ajax/change-password.php";
                changePasswordButton.prop('disabled', true);
                ajaxRequest(url, postData).then(result => {
                    toastr.success(result);
                }).catch(reason => {
                    toastr.error(reason);
                }).finally(() => {
                    form.reset();
                    changePasswordButton.prop('disabled', false);
                });
                return false;
            }
        });

        settingsForm.submit(function (event) {
            event.preventDefault();
            let postData = settingsForm.serialize();
            let url = "ajax/save-user-settings.php";
            saveSettingsButton.prop('disabled', true);
            ajaxRequest(url, postData).then(result => {
                toastr.success(result);
                if (languageInput.val() !== "<?= $logged_in_user->getLanguage() ?>") {
                    <?php if (isset($_COOKIE["DEVICE_ID"])) { ?>
                    if (typeof Android.changeLanguage === "function") {
                        Android.changeLanguage(languageInput.val());
                    }
                    <?php } ?>
                    setTimeout(() => {
                        document.location.href = `profile.php?language=${languageInput.val()}`;
                    }, 1000);
                } else {
                    let name = $('#nameInput').val();
                    <?php if (isset($_COOKIE["DEVICE_ID"])) { ?>
                    Android.changeName(name);
                    <?php } else { ?>
                    $('.user-name').html(he.encode(name));
                    <?php } ?>
                }
            }).catch(reason => {
                toastr.error(reason);
            }).finally(() => {
                saveSettingsButton.prop('disabled', false);
            });
        });
    });

</script>
</body>
</html>
