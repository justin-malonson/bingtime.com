<?php

require_once __DIR__ . "/includes/session.php";

if (isset($_SESSION["userID"])) {
    header("location:dashboard.php");
    exit();
}
if (!Setting::get("registration_enabled")) {
    http_response_code(403);
    exit("HTTP Error 403 - Forbidden");
}

$title = __("application_title") . " | " . __("register");
?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once __DIR__ . "/includes/head.php" ?>
    <style type="text/css">
        body {
            overflow: hidden;
        }

        @media only screen and (max-width: 768px) {
            .register-box {
                position: relative;
                top: 75px;
            }
        }
    </style>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body class="hold-transition register-page">

<?php require_once __DIR__ . "/includes/language-form.php"; ?>

<div class="register-box">
    <div class="register-logo">
        <img src="<?= Setting::get("logo_src"); ?>" style="width: 64px; height: 64px" alt="logo">
        <a href="index.php"><?= __("application_title"); ?></a>
    </div>

    <div class="register-box-body">
        <p class="login-box-msg"><?= __("register_demo"); ?></p>
        <form id="register" method="post">
            <div class="form-group has-feedback">
                <input type="text" name="name" id="nameInput" class="form-control" placeholder="<?= __("name"); ?>"
                       required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="email" name="email" id="emailInput" class="form-control"
                       placeholder="<?= __("email"); ?>" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="email" name="confirmEmail" id="confirmEmailInput" class="form-control"
                       placeholder="<?= __("confirm_email"); ?>"
                       required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <input type="text" name="timeZone" id="timeZoneInput" hidden>
            <?php if (Setting::get("recaptcha_enabled") && Setting::get("recaptcha_secret_key") && Setting::get("recaptcha_site_key")) { ?>
                <div class="form-group has-feedback">
                    <div class="g-recaptcha" data-sitekey="<?= Setting::get("recaptcha_site_key") ?>"></div>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-xs-8">
                    <a href="index.php"><?= __("register_sign_in_link"); ?></a>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" name="register" id="registerButton"
                            class="btn btn-primary btn-block btn-flat"><?= __("register"); ?>
                    </button>
                </div>
                <!-- /.col -->
            </div>
        </form>
    </div>
    <!-- /.form-box -->
</div>
<!-- /.register-box -->

<?php require_once __DIR__ . "/includes/footer.php" ?>
<?php require_once __DIR__ . "/includes/common-js.php" ?>

<script>
    $(function () {
        const registrationForm = $("#register");
        const registerButton = $('#registerButton');

        registrationForm.validate({
            rules: {
                email: "required",
                confirmEmail: {
                    equalTo: "#emailInput"
                }
            },
            submitHandler: function (form) {
                let postData = registrationForm.serialize();
                let url = "ajax/register-user.php";
                const options = {positionClass: "toast-top-center", closeButton: true};
                registerButton.prop('disabled', true);
                ajaxRequest(url, postData).then(result => {
                    toastr.success(result, null, options);
                    form.reset();
                }).catch(reason => {
                    toastr.error(reason, null, options);
                }).finally(() => {
                    registerButton.prop('disabled', false);
                });
                return false;
            }
        });

        $('#timeZoneInput').val(Intl.DateTimeFormat().resolvedOptions().timeZone);
    });
</script>
</body>
</html>