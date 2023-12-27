<?php
if (file_exists(__DIR__ . "/config.php")) {
    if (file_exists(__DIR__ . "/upgrade.php")) {
        header("location:upgrade.php");
        exit();
    }

    require_once __DIR__ . "/includes/session.php";
    date_default_timezone_set(TIMEZONE);

    if (file_exists(__DIR__ . "/install/index.php")) {
        if (!rmdir_recursive("install")) {
            $error = __("error_removing_install_directory");
        }
    }

    if (isset($_SESSION["userID"])) {
        header("location:dashboard.php");
        exit();
    }
} else {
    header("location:install/index.php");
    exit();
}

$title = __("application_title") . " | " . __("sign_in");
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
            .login-box {
                position: relative;
                top: 75px;
            }
        }
    </style>
</head>
<body class="hold-transition login-page">

<?php require_once __DIR__ . "/includes/language-form.php"; ?>

<div class="login-box">
    <div class="login-logo">
        <a href="index.php">
            <img src="<?= Setting::get("logo_src"); ?>" style="width: 64px; height: 64px" alt="logo">
            <?= __("application_title"); ?></a>
    </div>
    <!-- /.login-logo -->

    <?php if (isset($error)) { ?>

        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-ban"></i>&nbsp;<?= __("error_dialog_title"); ?></h4>
            <?php echo $error; ?>
        </div>

    <?php } ?>

    <div class="login-box-body">
        <p class="login-box-msg"><?= __("sign_in_message") ?></p>

        <form action="ajax/login-form.php" id="loginForm" method="post">
            <div class="form-group has-feedback">
                <input type="email" name="email" class="form-control" placeholder="<?= __("email") ?>" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control" placeholder="<?= __("password") ?>"
                       required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <a href="reset-password.php"><?= __("forget_password_link") ?></a>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" name="signIn" id="signInButton"
                            class="btn btn-primary btn-block btn-flat"><?= __("sign_in") ?></button>
                </div>
                <!-- /.col -->
            </div>
        </form>

    </div>
    <!-- /.login-box-body -->

    <?php if (Setting::get("registration_enabled")) { ?>
        <div class="box-footer">
            <div class="text-center">
                <?= __("do_not_have_an_account"); ?>&nbsp;<a href="register.php"><?= __("register"); ?></a>
            </div>
        </div>
    <?php } ?>
    <!-- /.login-box-footer -->
</div>
<!-- /.login-box -->

<?php require_once __DIR__ . "/includes/footer.php" ?>
<?php require_once __DIR__ . "/includes/common-js.php" ?>

<script type="text/javascript">
    $(function () {
        const loginForm = $('#loginForm');
        const signInButton = $('#signInButton');

        loginForm.submit(function (event) {
            event.preventDefault();
            signInButton.prop('disabled', true);
            const options = {positionClass: "toast-top-center", closeButton: false};
            ajaxRequest("ajax/login-form.php", loginForm.serialize()).then(() => {
                document.location.href = "dashboard.php"
            }).catch(reason => {
                toastr.error(reason, null, options);
                event.target.reset();
            }).finally(() => {
                signInButton.prop('disabled', false);
            });
        });
    });
</script>
</body>
</html>