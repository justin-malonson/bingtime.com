<?php
/**
 * @var User $logged_in_user
 */
require_once __DIR__ . "/includes/login.php";

if (!$_SESSION["isAdmin"]) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}

$title = __("application_title") . " | " . __("settings");

$languageFiles = getLanguageFiles();

require_once __DIR__ . "/includes/header.php";
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= __("settings"); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <form role="form" id="settingsForm" method="post" enctype="multipart/form-data">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab"
                                                  aria-expanded="false"><?= __("general"); ?></a></li>
                            <li><a href="#tab_2" data-toggle="tab" aria-expanded="true">SMTP</a></li>
                            <li><a href="#tab_3" data-toggle="tab" aria-expanded="true"><?= __("emails"); ?></a></li>
                            <li><a href="#tab_4" data-toggle="tab"><?= __("registration"); ?></a></li>
                            <li><a href="#tab_5" data-toggle="tab"><?= __("messages"); ?></a></li>
                            <li><a href="#tab_6" data-toggle="tab"><?= __("features"); ?></a></li>
                            <li><a href="#tab_7" data-toggle="tab"><?= __("payment_gateway"); ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="panel-title"><?= __("appearance"); ?></div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label for="siteNameInput"><?= __("site_name"); ?></label>
                                                    <input type="text" maxlength="50" name="application_title"
                                                           class="form-control" id="siteNameInput"
                                                           value="<?= htmlentities(Setting::get("application_title"), ENT_QUOTES); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="siteDescriptionInput"><?= __("site_description"); ?></label>
                                                    <input type="text" maxlength="255" name="application_description"
                                                           class="form-control"
                                                           id="siteDescriptionInput"
                                                           value="<?= htmlentities(Setting::get("application_description"), ENT_QUOTES); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="languageInput"><?= __("language"); ?></label>
                                                    <select name="default_language" id="languageInput"
                                                            class="form-control select2" style="width: 100%">
                                                        <?php
                                                        foreach ($languageFiles as $languageFile) {
                                                            createOption(ucfirst($languageFile), $languageFile, $languageFile == Setting::get("default_language"));
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="skinInput"><?= __("skin_setting"); ?></label>
                                                    <select name="skin" id="skinInput"
                                                            class="form-control select2" style="width: 100%">
                                                        <option value="black" <?= Setting::get("skin") == "black" ? 'selected' : '' ?>><?= __("black") ;?></option>
                                                        <option value="black-light" <?= Setting::get("skin") == "black-light" ? 'selected' : '' ?>><?= __("black_light") ;?></option>
                                                        <option value="blue" <?= Setting::get("skin") == "blue" ? 'selected' : '' ?>><?= __("blue") ;?></option>
                                                        <option value="blue-light" <?= Setting::get("skin") == "blue-light" ? 'selected' : '' ?>><?= __("blue_light") ;?></option>
                                                        <option value="green" <?= Setting::get("skin") == "green" ? 'selected' : '' ?>><?= __("green") ;?></option>
                                                        <option value="green-light" <?= Setting::get("skin") == "green-light" ? 'selected' : '' ?>><?= __("green_light") ;?></option>
                                                        <option value="purple" <?= Setting::get("skin") == "purple" ? 'selected' : '' ?>><?= __("purple") ;?></option>
                                                        <option value="purple-light" <?= Setting::get("skin") == "purple-light" ? 'selected' : '' ?>><?= __("purple_light") ;?></option>
                                                        <option value="red" <?= Setting::get("skin") == "red" ? 'selected' : '' ?>><?= __("red") ;?></option>
                                                        <option value="red-light" <?= Setting::get("skin") == "red-light" ? 'selected' : '' ?>><?= __("red_light") ;?></option>
                                                        <option value="yellow" <?= Setting::get("skin") == "yellow" ? 'selected' : '' ?>><?= __("yellow") ;?></option>
                                                        <option value="yellow-light" <?= Setting::get("skin") == "yellow-light" ? 'selected' : '' ?>><?= __("yellow_light") ;?></option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="logoInput"><?= __("logo"); ?></label>
                                                    <input type="file" name="logo_src"
                                                           id="logoInput">
                                                </div>
                                                <div class="form-group">
                                                    <label for="faviconInput"><?= __("favicon"); ?></label>
                                                    <input type="file" name="favicon_src"
                                                           id="faviconInput">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="panel-title"><?= __("links"); ?></div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label for="androidAppURLInput">
                                                        <?= __("android_app"); ?>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip"
                                                           title="<?= __('tooltip_android_app_url'); ?>"></i>
                                                    </label>
                                                    <input type="text" name="application_url" class="form-control"
                                                           id="androidAppURLInput"
                                                           value="<?= Setting::get("application_url"); ?>"
                                                           required="required">
                                                </div>
                                                <div class="form-group">
                                                    <label for="unsubscribeURLInput">
                                                        <?= __("unsubscribe"); ?>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip"
                                                           title="<?= __('tooltip_unsubscribe_url'); ?>"></i>
                                                    </label>
                                                    <input type="text" name="unsubscribe_url" class="form-control"
                                                           id="unsubscribeURLInput"
                                                           value="<?= str_replace("%server%", getServerURL(), Setting::get("unsubscribe_url")); ?>"
                                                           required="required">
                                                </div>
                                                <div class="form-group">
                                                    <label for="getCreditsURLInput"><?= __("get_credits"); ?></label>
                                                    <input type="text" name="get_credits_url" class="form-control"
                                                           id="getCreditsURLInput"
                                                           value="<?= Setting::get("get_credits_url"); ?>"
                                                           required="required">
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <div class="panel-title"><?= __("copyright"); ?></div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="form-group">
                                                            <label for="copyrightNameInput"><?= __("name"); ?></label>
                                                            <input type="text" maxlength="50" name="company_name"
                                                                   class="form-control"
                                                                   id="copyrightNameInput"
                                                                   value="<?= htmlentities(Setting::get("company_name"), ENT_QUOTES); ?>"
                                                                   required="required">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="copyrightURLInput">URL</label>
                                                            <input type="text" name="company_url" class="form-control"
                                                                   id="copyrightURLInput"
                                                                   value="<?= Setting::get("company_url"); ?>"
                                                                   required="required">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="panel-title"><?= __("announcement_setting"); ?></div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <textarea name="announcement" rows="4" class="form-control" title="<?= __("announcement_setting"); ?>"
                                                              id="announcementInput"><?= Setting::get("announcement") ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_2">
                                <div class="form-group">
                                    <label for="fromEmailNameInput"><?= __("from_name"); ?></label>
                                    <input type="text" name="from_email_name" class="form-control"
                                           id="fromEmailNameInput"
                                           placeholder="<?= htmlentities($logged_in_user->getName(), ENT_QUOTES); ?>"
                                           value="<?= Setting::get("from_email_name") ?>">
                                </div>
                                <div class="form-group">
                                    <label for="fromEmailAddressInput"><?= __("from_address"); ?></label>
                                    <input type="email" name="from_email_address" class="form-control"
                                           id="fromEmailAddressInput"
                                           placeholder="<?= $logged_in_user->getEmail(); ?>"
                                           value="<?= Setting::get("from_email_address") ?>">
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-title">SMTP</div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="enableSMTPInput"><?= __("enabled"); ?></label>
                                            <select name="smtp_enabled" id="enableSMTPInput"
                                                    class="form-control toggleInput select2" data-for=".smtp"
                                                    style="width: 100%">
                                                <option value="0" <?= Setting::get("smtp_enabled") == '0' ? 'selected' : '' ?>><?= __("no"); ?></option>
                                                <option value="1" <?= Setting::get("smtp_enabled") == '1' ? 'selected' : '' ?>><?= __("yes"); ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="hostnameInput"><?= __("hostname"); ?></label>
                                            <input type="text" name="smtp_hostname" class="form-control smtp"
                                                   id="hostnameInput"
                                                   placeholder="smtp.gmail.com"
                                                   value="<?= Setting::get("smtp_hostname"); ?>"
                                                   required="required">
                                        </div>
                                        <div class="form-group">
                                            <label for="portInput"><?= __("port"); ?></label>
                                            <input type="number" name="smtp_port" min="0" max="65535"
                                                   class="form-control smtp"
                                                   id="portInput" placeholder="587"
                                                   value="<?= Setting::get("smtp_port"); ?>"
                                                   required="required">
                                        </div>
                                        <div class="form-group">
                                            <label for="encryptionInput"><?= __("encryption"); ?></label>
                                            <input type="text" name="smtp_encryption" maxlength="10"
                                                   class="form-control smtp"
                                                   id="encryptionInput" placeholder="tls"
                                                   value="<?= Setting::get("smtp_encryption"); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="usernameInput"><?= __("username"); ?></label>
                                            <input type="text" name="smtp_username" maxlength="320"
                                                   class="form-control smtp"
                                                   id="usernameInput" placeholder="example@gmail.com"
                                                   value="<?= Setting::get("smtp_username"); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="passwordInput"><?= __("password"); ?></label>
                                            <input type="password" name="smtp_password" class="form-control smtp"
                                                   id="passwordInput" placeholder="password"
                                                   value="<?= Setting::get("smtp_password"); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="sslVerificationInput"><?= __("ssl_verification"); ?></label>
                                            <select name="smtp_ssl_verification" id="sslVerificationInput" class="form-control smtp select2"
                                                    style="width: 100%;" required>
                                                <option value="1" <?= Setting::get("smtp_ssl_verification") == '1' ? 'selected' : '' ?>><?= __("on_recommended"); ?></option>
                                                <option value="0" <?= Setting::get("smtp_ssl_verification") == '0' ? 'selected' : '' ?>><?= __("off"); ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="debugInput">
                                                <?= __("debug"); ?>&nbsp;
                                                <?php if (file_exists(__DIR__ . "/smtp-debug-info.html")) { ?>
                                                    (<a href="#" data-toggle="modal"
                                                        data-target="#modal-smtp-debug-info"><?= __("show_debug_info"); ?></a>)
                                                <?php } ?>
                                            </label>
                                            <select name="smtp_debug" id="debugInput" class="form-control smtp select2"
                                                    style="width: 100%;" required>
                                                <option value="0" <?= Setting::get("smtp_debug") == '0' ? 'selected' : '' ?>><?= __("off"); ?></option>
                                                <option value="1" <?= Setting::get("smtp_debug") == '1' ? 'selected' : '' ?>>Client</option>
                                                <option value="2" <?= Setting::get("smtp_debug") == '2' ? 'selected' : '' ?>>Server</option>
                                                <option value="3" <?= Setting::get("smtp_debug") == '3' ? 'selected' : '' ?>>Connection</option>
                                                <option value="4" <?= Setting::get("smtp_debug") == '4' ? 'selected' : '' ?>>Low Level</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_3">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-title"><?= __("registration"); ?></div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="registrationEmailSubjectInput"><?= __("subject"); ?></label>
                                            <input type="text" name="register_email_subject" class="form-control"
                                                   id="registrationEmailSubjectInput"
                                                   value="<?= htmlentities(Setting::get("register_email_subject"), ENT_QUOTES); ?>"
                                                   required="required">
                                        </div>
                                        <div class="form-group">
                                            <label for="registrationEmailInput"><?= __("email"); ?></label>
                                            <textarea name="register_email_body" rows="10" class="form-control"
                                                      id="registrationEmailInput"
                                                      required="required"><?= Setting::get("register_email_body") ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-title"><?= __("reset_password"); ?></div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="resetPasswordEmailSubjectInput"><?= __("subject"); ?></label>
                                            <input type="text" name="reset_password_email_subject" class="form-control"
                                                   id="resetPasswordEmailSubjectInput"
                                                   value="<?= htmlentities(Setting::get("reset_password_email_subject"), ENT_QUOTES); ?>"
                                                   required="required">
                                        </div>
                                        <div class="form-group">
                                            <label for="resetPasswordEmailInput"><?= __("email"); ?></label>
                                            <textarea name="reset_password_email_body" rows="10" class="form-control"
                                                      id="resetPasswordEmailInput"
                                                      required="required"><?= Setting::get("reset_password_email_body") ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-title"><?= __("reset_password_link"); ?></div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="resetPasswordLinkEmailSubjectInput"><?= __("subject"); ?></label>
                                            <input type="text" name="reset_password_link_email_subject"
                                                   class="form-control"
                                                   id="resetPasswordLinkEmailSubjectInput"
                                                   value="<?= htmlentities(Setting::get("reset_password_link_email_subject"), ENT_QUOTES); ?>"
                                                   required="required">
                                        </div>
                                        <div class="form-group">
                                            <label for="resetPasswordLinkEmailInput"><?= __("email"); ?></label>
                                            <textarea name="reset_password_link_email_body" rows="10"
                                                      class="form-control"
                                                      id="resetPasswordLinkEmailInput"
                                                      required="required"><?= Setting::get("reset_password_link_email_body") ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-title"><?= __("user_limits_update"); ?></div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="userLimitsUpdateEmailSubjectInput"><?= __("subject"); ?></label>
                                            <input type="text" name="edit_user_subject" class="form-control"
                                                   id="userLimitsUpdateEmailSubjectInput"
                                                   value="<?= htmlentities(Setting::get("edit_user_subject"), ENT_QUOTES); ?>"
                                                   required="required">
                                        </div>
                                        <div class="form-group">
                                            <label for="userLimitsUpdateEmailInput"><?= __("email"); ?></label>
                                            <textarea name="edit_user_email_body" rows="10" class="form-control"
                                                      id="userLimitsUpdateEmailInput"
                                                      required="required"><?= Setting::get("edit_user_email_body") ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_4">
                                <div class="form-group">
                                    <label for="enableRegistrationInput"><?= __("enable_registration"); ?></label>
                                    <select name="registration_enabled" id="enableRegistrationInput"
                                            class="form-control select2"
                                            style="width: 100%">
                                        <option value="0" <?= Setting::get("registration_enabled") == '0' ? 'selected' : '' ?>><?= __("no"); ?></option>
                                        <option value="1" <?= Setting::get("registration_enabled") == '1' ? 'selected' : '' ?>><?= __("yes"); ?></option>
                                    </select>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-title"><?= __("default_settings_new_users"); ?></div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="defaultDelayInput"><?= __("delay_setting"); ?></label>
                                            <input type="text" name="default_delay"
                                                   class="form-control" id="defaultDelayInput"
                                                   value="<?= Setting::get("default_delay") != "" ? Setting::get("default_delay") : "2"; ?>"
                                                   required="required">
                                        </div>
                                        <div class="form-group">
                                            <label for="defaultDeliveryReportsEnabledInput"><?= __("new_user_report_delivery_setting"); ?></label>
                                            <select name="default_delivery_reports_enabled"
                                                    id="defaultDeliveryReportsEnabledInput"
                                                    class="form-control select2"
                                                    style="width: 100%">
                                                <option value="0" <?= Setting::get("default_delivery_reports_enabled") == '0' ? 'selected' : '' ?>><?= __("no"); ?></option>
                                                <option value="1" <?= Setting::get("default_delivery_reports_enabled") == '1' ? 'selected' : '' ?>><?= __("yes"); ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="defaultUseProgressiveQueueInput"><?= __("use_progressive_queue_setting"); ?></label>
                                            <select name="default_use_progressive_queue"
                                                    id="defaultUseProgressiveQueueInput"
                                                    class="form-control select2"
                                                    style="width: 100%">
                                                <option value="0" <?= Setting::get("default_use_progressive_queue") == '0' ? 'selected' : '' ?>><?= __("no"); ?></option>
                                                <option value="1" <?= Setting::get("default_use_progressive_queue") == '1' ? 'selected' : '' ?>><?= __("yes"); ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="defaultAutoRetryEnabledInput"><?= __("auto_retry_setting"); ?></label>
                                            <select name="default_auto_retry_enabled"
                                                    id="defaultAutoRetryEnabledInput"
                                                    class="form-control select2"
                                                    style="width: 100%">
                                                <option value="0" <?= Setting::get("default_auto_retry_enabled") == '0' ? 'selected' : '' ?>><?= __("no"); ?></option>
                                                <option value="1" <?= Setting::get("default_auto_retry_enabled") == '1' ? 'selected' : '' ?>><?= __("yes"); ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="defaultUssdDelayInput"><?= __("ussd_delay"); ?></label>
                                            <input type="number" name="default_ussd_delay"
                                                   min="0" max="120"
                                                   class="form-control" id="defaultUssdDelayInput"
                                                   value="<?= Setting::get("default_ussd_delay") != "" ? Setting::get("default_ussd_delay") : "0"; ?>"
                                                   required="required">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-title"><?= __("new_user_limitations"); ?></div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="trialCreditsInput"><input type="checkbox" id="toggleTrialCreditsInput"
                                                                                  onchange="disableInput('#toggleTrialCreditsInput', '#trialCreditsInput')" autocomplete="off"
                                                                                  <?php if (Setting::get("default_credits") != null) { ?> checked <?php } ?>>&nbsp;<?= __("credits"); ?>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-android"></i>
                                                </div>
                                                <input type="number" min="0" name="default_credits"
                                                       class="form-control" id="trialCreditsInput"
                                                       value="<?= Setting::get("default_credits") != "" ? Setting::get("default_credits") : "200"; ?>"
                                                       required="required" <?php if (Setting::get("default_credits") == null) { ?> disabled <?php } ?>>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="trialDevicesLimitInput"><input type="checkbox" id="toggleTrialDevicesLimitInput"
                                                                                       onchange="disableInput('#toggleTrialDevicesLimitInput', '#trialDevicesLimitInput')" autocomplete="off"
                                                                                       <?php if (Setting::get("default_devices_limit") != null) { ?> checked <?php } ?>>&nbsp;<?= __("max_devices"); ?>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-android"></i>
                                                </div>
                                                <input type="number" min="0" name="default_devices_limit"
                                                       class="form-control" id="trialDevicesLimitInput"
                                                       value="<?= Setting::get("default_devices_limit") != "" ? Setting::get("default_devices_limit") : "2"; ?>"
                                                       required="required" <?php if (Setting::get("default_devices_limit") == null) { ?> disabled <?php } ?>>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="trialContactsLimitInput"><input type="checkbox" id="toggleTrialContactsLimitInput"
                                                                                       onchange="disableInput('#toggleTrialContactsLimitInput', '#trialContactsLimitInput')" autocomplete="off"
                                                                                       <?php if (Setting::get("default_contacts_limit") != null) { ?> checked <?php } ?>>&nbsp;<?= __("max_contacts"); ?>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-android"></i>
                                                </div>
                                                <input type="number" min="0" name="default_contacts_limit"
                                                       class="form-control" id="trialContactsLimitInput"
                                                       value="<?= Setting::get("default_contacts_limit") != "" ? Setting::get("default_contacts_limit") : "200"; ?>"
                                                       required="required" <?php if (Setting::get("default_contacts_limit") == null) { ?> disabled <?php } ?>>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="trialExpireIntervalInput"><?= __("expires_after"); ?></label>
                                            <select name="default_expire_interval" id="trialExpireIntervalInput"
                                                    class="form-control select2"
                                                    style="width: 100%">
                                                <option value="" <?= empty(Setting::get("default_expire_interval")) ? 'selected' : '' ?>><?= __("never"); ?></option>
                                                <option value="604800" <?= Setting::get("default_expire_interval") == '604800' ? 'selected' : '' ?>>7 <?= __("days"); ?></option>
                                                <option value="1296000" <?= Setting::get("default_expire_interval") == '1296000' ? 'selected' : '' ?>>15 <?= __("days"); ?></option>
                                                <option value="2592000" <?= Setting::get("default_expire_interval") == '2592000' ? 'selected' : '' ?>>30 <?= __("days"); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-title">reCaptcha</div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="enableRecaptchaInput"><?= __("enabled"); ?></label>
                                            <select name="recaptcha_enabled" id="enableRecaptchaInput"
                                                    class="form-control toggleInput select2" data-for=".recaptcha"
                                                    style="width: 100%" required>
                                                <option value="0" <?= Setting::get("recaptcha_enabled") == '0' ? 'selected' : '' ?>><?= __("no"); ?></option>
                                                <option value="1" <?= Setting::get("recaptcha_enabled") == '1' ? 'selected' : '' ?>><?= __("yes"); ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="secretKeyInput"><?= __("secret_key"); ?></label>
                                            <input type="text" name="recaptcha_secret_key" maxlength="255"
                                                   class="form-control recaptcha" id="secretKeyInput"
                                                   value="<?= Setting::get("recaptcha_secret_key"); ?>"
                                                   required="required">
                                        </div>
                                        <div class="form-group">
                                            <label for="siteKeyInput"><?= __("site_key"); ?></label>
                                            <input type="text" name="recaptcha_site_key" maxlength="255"
                                                   class="form-control recaptcha" id="siteKeyInput"
                                                   value="<?= Setting::get("recaptcha_site_key"); ?>"
                                                   required="required">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_5">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-title"><?= __("auto_retry_setting"); ?></div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="maxRetriesInput">
                                                <?= __("max_retries_label"); ?>
                                                <i class="fa fa-info-circle" data-toggle="tooltip"
                                                   title="<?= __('tooltip_max_retries'); ?>"></i>
                                            </label>
                                            <input type="number" min="1" max="10" name="max_retries"
                                                   class="form-control" id="maxRetriesInput"
                                                   value="<?= Setting::get("max_retries") ? Setting::get("max_retries") : "1"; ?>"
                                                   required="required">
                                        </div>
                                        <div class="form-group">
                                            <label for="retryTimeIntervalInput">
                                                <?= __("retry_time_interval_label"); ?>
                                                <i class="fa fa-info-circle" data-toggle="tooltip"
                                                   title="<?= __('tooltip_retry_time_interval'); ?>"></i>
                                            </label>
                                            <select name="retry_time_interval" id="retryTimeIntervalInput"
                                                    class="form-control select2"
                                                    style="width: 100%">
                                                <option value="" <?= Setting::get("retry_time_interval") == '900' ? 'selected' : '' ?>>15 <?= __("minutes"); ?></option>
                                                <option value="604800" <?= Setting::get("retry_time_interval") == '1800' ? 'selected' : '' ?>>30 <?= __("minutes"); ?></option>
                                                <option value="1296000" <?= Setting::get("retry_time_interval") == '3600' ? 'selected' : '' ?>>1 <?= __("hour"); ?></option>
                                                <option value="2592000" <?= Setting::get("retry_time_interval") == '7200' ? 'selected' : '' ?>>2 <?= __("hours"); ?></option>
                                                <option value="2592000" <?= Setting::get("retry_time_interval") == '21600' ? 'selected' : '' ?>>6 <?= __("hours"); ?></option>
                                                <option value="2592000" <?= Setting::get("retry_time_interval") == '43200' ? 'selected' : '' ?>>12 <?= __("hours"); ?></option>
                                                <option value="2592000" <?= Setting::get("retry_time_interval") == '86400' ? 'selected' : '' ?>>1 <?= __("day"); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-title"><?= __("message_footer_demo_users"); ?></div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="footerTextEnabledInput"><?= __("enabled"); ?></label>
                                            <select name="footer_text_enabled"
                                                    id="footerTextEnabledInput"
                                                    class="form-control toggleInput select2" data-for=".footer-text"
                                                    style="width: 100%">
                                                <option value="0" <?= Setting::get("footer_text_enabled") == '0' ? 'selected' : '' ?>><?= __("no"); ?></option>
                                                <option value="1" <?= Setting::get("footer_text_enabled") == '1' ? 'selected' : '' ?>><?= __("yes"); ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="footerTextInput"><?= __("footer_text_label"); ?></label>
                                            <input type="text" maxlength="255" name="footer_text"
                                                   class="form-control footer-text"
                                                   id="footerTextInput"
                                                   value="<?= htmlentities(Setting::get("footer_text"), ENT_QUOTES); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-title"><?= __("general") ?></div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="enableRealtimeDashboardInput"><?= __("realtime_dashboard_updates"); ?></label>
                                            <select name="realtime_dashboard_enabled" id="enableRealtimeDashboardInput"
                                                    class="form-control select2"
                                                    style="width: 100%" required>
                                                <option value="0" <?= Setting::get("realtime_dashboard_enabled") == '0' ? 'selected' : '' ?>><?= __("disabled"); ?></option>
                                                <option value="1" <?= Setting::get("realtime_dashboard_enabled") == '1' ? 'selected' : '' ?>><?= __("enabled"); ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="enableReceivedMessageNotificationInput"><?= __("received_message_notification"); ?></label>
                                            <select name="received_message_notification_enabled" id="enableReceivedMessageNotificationInput"
                                                    class="form-control select2"
                                                    style="width: 100%" required>
                                                <option value="0" <?= Setting::get("received_message_notification_enabled") == '0' ? 'selected' : '' ?>><?= __("disabled"); ?></option>
                                                <option value="1" <?= Setting::get("received_message_notification_enabled") == '1' ? 'selected' : '' ?>><?= __("enabled"); ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="enableSmsToEmailInput"><?= __("send_received_messages_to_email"); ?></label>
                                            <select name="sms_to_email_enabled" id="enableSmsToEmailInput"
                                                    class="form-control select2"
                                                    style="width: 100%" required>
                                                <option value="0" <?= Setting::get("sms_to_email_enabled") == '0' ? 'selected' : '' ?>><?= __("disabled"); ?></option>
                                                <option value="1" <?= Setting::get("sms_to_email_enabled") == '1' ? 'selected' : '' ?>><?= __("enabled"); ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="depleteCreditForEachSmsPartInput"><?= __("deplete_credit_for_each_sms_part_label"); ?></label>
                                            <select name="deplete_credit_for_each_sms_part" id="depleteCreditForEachSmsPartInput"
                                                    class="form-control select2"
                                                    style="width: 100%" required>
                                                <option value="0" <?= Setting::get("deplete_credit_for_each_sms_part") == '0' ? 'selected' : '' ?>><?= __("disabled"); ?></option>
                                                <option value="1" <?= Setting::get("deplete_credit_for_each_sms_part") == '1' ? 'selected' : '' ?>><?= __("enabled"); ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="useCreditsForReceivedMessagesInput"><?= __("use_credits_for_received_messages"); ?></label>
                                            <select name="use_credits_for_received_messages_enabled" id="useCreditsForReceivedMessagesInput"
                                                    class="form-control select2"
                                                    style="width: 100%" required>
                                                <option value="0" <?= Setting::get("use_credits_for_received_messages_enabled") == '0' ? 'selected' : '' ?>><?= __("disabled"); ?></option>
                                                <option value="1" <?= Setting::get("use_credits_for_received_messages_enabled") == '1' ? 'selected' : '' ?>><?= __("enabled"); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-title"><?= __("integrations") ?></div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="firebaseServiceAccountInput"><?= __("firebase_service_account"); ?></label>
                                            <input type="file" name="firebase_service_account" id="firebaseServiceAccountInput">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_7">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-title">PayPal</div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="enablePayPalInput"><?= __("enabled"); ?></label>
                                            <select name="paypal_enabled" id="enablePayPalInput"
                                                    class="form-control toggleInput select2" data-for=".paypal"
                                                    style="width: 100%" required>
                                                <option value="0" <?= Setting::get("paypal_enabled") == '0' ? 'selected' : '' ?>><?= __("no"); ?></option>
                                                <option value="1" <?= Setting::get("paypal_enabled") == '1' ? 'selected' : '' ?>><?= __("yes"); ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="paypalCredentialsTypeInput"><?= __("credentials_type"); ?></label>
                                            <select name="paypal_sandbox" id="paypalCredentialsTypeInput"
                                                    class="form-control select2 paypal" style="width: 100%">
                                                <option value="1" <?= Setting::get("paypal_sandbox") == '1' ? 'selected' : '' ?>>Sandbox</option>
                                                <option value="0" <?= Setting::get("paypal_sandbox") == '0' ? 'selected' : '' ?>>Live</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="paypalClientIDInput"><?= __("client_id"); ?></label>
                                            <input type="text" name="paypal_client_id" maxlength="255"
                                                   class="form-control paypal" id="paypalClientIDInput"
                                                   value="<?= Setting::get("paypal_client_id"); ?>" required="required">
                                        </div>
                                        <div class="form-group">
                                            <label for="paypalSecretInput"><?= __("secret"); ?></label>
                                            <input type="text" name="paypal_secret" maxlength="255"
                                                   class="form-control paypal" id="paypalSecretInput"
                                                   value="<?= Setting::get("paypal_secret"); ?>" required="required">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- nav-tabs-custom -->
                    <button type="submit" id="button-save-settings" class="btn btn-primary"><i
                                class="fa fa-save"></i>&nbsp;<?= __("save"); ?>
                    </button>
                </form>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php if (file_exists(__DIR__ . "/smtp-debug-info.html")) { ?>
    <div class="modal fade" id="modal-smtp-debug-info">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?= __("smtp_debug_information"); ?></h4>
                </div>
                <div class="modal-body">
                    <?= file_get_contents(__DIR__ . "/smtp-debug-info.html"); ?>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
<?php } ?>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
<?php require_once __DIR__ . "/includes/common-js.php"; ?>
<!-- he -->
<script src="components/he/he.js"></script>
<!-- Trumbowyg -->
<script src="components/trumbowyg/dist/trumbowyg.min.js"></script>
<!-- Trumbowyg plugins -->
<script src="components/trumbowyg/dist/plugins/base64/trumbowyg.base64.min.js"></script>
<script src="components/trumbowyg/dist/plugins/pasteembed/trumbowyg.pasteembed.min.js"></script>
<script src="components/trumbowyg/dist/plugins/colors/trumbowyg.colors.min.js"></script>
<script src="components/trumbowyg/dist/plugins/history/trumbowyg.history.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('textarea').trumbowyg({
            btnsDef: {
                // Create a new dropdown
                image: {
                    dropdown: ['insertImage', 'base64'],
                    ico: 'insertImage'
                }
            },
            // Redefine the button pane
            btns: [
                ['viewHTML'],
                ['formatting'],
                ['historyUndo', 'historyRedo'],
                ['strong', 'em', 'del'],
                ['superscript', 'subscript'],
                ['foreColor', 'backColor'],
                ['link'],
                ['image'], // Our fresh created dropdown
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                ['unorderedList', 'orderedList'],
                ['horizontalRule'],
                ['removeformat'],
                ['fullscreen']
            ]
        })

        const toggleInput = $('.toggleInput');
        const settingsForm = $('#settingsForm');
        const companyURL = $('#company_url');
        const saveSettingsButton = $('#button-save-settings');

        toggleInput.change(function (event) {
            event.preventDefault();
            let elements = $($(this).data('for'));
            let disabled = $(this).val() === '0';
            elements.prop('disabled', disabled);
        });

        toggleInput.trigger('change');

        let skin = $('#skinInput').val();
        settingsForm.submit(function (event) {
            event.preventDefault();
            saveSettingsButton.prop('disabled', true);
            let formData = new FormData(this);
            ajaxRequest("ajax/save-settings.php", formData).then(result => {
                toastr.success(result);
                if ($('#logoInput').get(0).files.length === 0 && $('#faviconInput').get(0).files.length === 0 && skin === $('#skinInput').val()) {
                    let applicationTitle = he.encode($('#siteNameInput').val());
                    $('#application-title').html(applicationTitle);
                    $('title').html(`${applicationTitle} | <?=__("settings")?>`);
                    companyURL.html(he.encode($('#copyrightNameInput').val()));
                    companyURL.attr('href', $('#copyrightURLInput').val());
                    $('meta[name="description"]').attr('content', he.encode($('#siteDescriptionInput').val()));
                } else {
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            }).catch(reason => {
                toastr.error(reason);
            }).finally(() => {
                saveSettingsButton.prop('disabled', false);
            });
        })
    });
</script>
</body>
</html>